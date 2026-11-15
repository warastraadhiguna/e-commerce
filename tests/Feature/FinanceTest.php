<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Filament\Pages\Finance;
use App\Filament\Resources\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Models\Expense;
use App\Models\Order;
use App\Models\User;
use App\Support\FinanceReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FinanceTest extends TestCase
{
    use RefreshDatabase;

    private function order(array $attributes = []): Order
    {
        $buyer = User::factory()->buyer()->create();

        return Order::query()->create(array_merge([
            'order_number' => 'TEST-'.uniqid(), 'user_id' => $buyer->id,
            'customer_name' => $buyer->name, 'customer_role' => 'buyer',
            'shipping_recipient_name' => $buyer->name, 'shipping_address_line' => 'Alamat uji',
            'status' => OrderStatus::Pending, 'total_amount' => 500000,
            'checked_out_at' => now(),
        ], $attributes));
    }

    public function test_only_paid_orders_in_selected_month_count_as_income(): void
    {
        $this->order(['payment_status' => 'paid', 'paid_at' => '2026-11-10 09:00:00']);
        $this->order(['payment_status' => 'paid', 'paid_at' => '2026-10-31 23:59:59']);
        $this->order(['payment_status' => 'paid', 'paid_at' => '2026-12-01 00:00:00']);
        $this->order(['payment_status' => 'unpaid', 'total_amount' => 250000]);
        $this->order(['payment_status' => 'unpaid', 'status' => OrderStatus::Cancelled]);
        Expense::query()->create(['reference' => 'EXP-1', 'description' => 'Kemasan', 'category' => 'operational', 'amount' => 50000, 'expense_date' => '2026-11-11']);
        Expense::query()->create(['reference' => 'EXP-2', 'description' => 'Biaya bulan lalu', 'category' => 'other', 'amount' => 90000, 'expense_date' => '2026-10-31']);

        $report = app(FinanceReport::class)->forMonth('2026-11');
        $this->assertSame(500000.0, $report['income']);
        $this->assertSame(50000.0, $report['expenses']);
        $this->assertSame(450000.0, $report['balance']);
        $this->assertSame(250000.0, $report['receivables']);
        $this->assertCount(2, $report['entries']);
        $this->assertCount(30, $report['daily']);
    }

    public function test_payment_timestamp_is_created_once_and_cleared_when_unpaid(): void
    {
        $order = $this->order();
        $this->travelTo(now()->setDate(2026, 11, 16)->setTime(9, 0));
        $order->update(['payment_status' => 'paid', 'payment_method' => 'transfer']);
        $timestamp = $order->paid_at->toDateTimeString();
        $this->travel(1)->days();
        $order->update(['internal_notes' => 'Konfirmasi']);
        $this->assertSame($timestamp, $order->fresh()->paid_at->toDateTimeString());
        $order->update(['payment_status' => 'unpaid']);
        $this->assertNull($order->fresh()->paid_at);
    }

    public function test_admin_can_open_finance_and_create_expense_with_validation(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->get('/management/finance')->assertOk()->assertSeeText('Laporan Keuangan');
        $this->get('/management/expenses')->assertOk();
        Livewire::test(CreateExpense::class)->fillForm([
            'reference' => 'EXP-TEST', 'description' => 'Kemasan pesanan', 'category' => 'operational',
            'amount' => -10, 'expense_date' => today()->toDateString(),
        ])->call('create')->assertHasFormErrors(['amount' => 'min'])
            ->fillForm(['amount' => 25000])->call('create')->assertHasNoFormErrors();
        $this->assertDatabaseHas('expenses', ['reference' => 'EXP-TEST', 'amount' => 25000]);
        Livewire::test(Finance::class)->call('export')->assertFileDownloaded('laporan-keuangan-'.now()->format('Y-m').'.csv');
    }

    public function test_buyer_cannot_access_finance_or_expenses(): void
    {
        $this->actingAs(User::factory()->buyer()->create());
        $this->get('/management/finance')->assertForbidden();
        $this->get('/management/expenses')->assertForbidden();
    }

    public function test_admin_can_confirm_payment_from_order_form(): void
    {
        $order = $this->order();
        $this->actingAs(User::factory()->admin()->create());
        Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
            ->fillForm(['payment_status' => 'paid', 'payment_method' => null])
            ->call('save')->assertHasFormErrors(['payment_method' => 'required'])
            ->fillForm(['payment_method' => 'transfer', 'payment_reference' => 'TEST-TRANSFER'])
            ->call('save')->assertHasNoFormErrors();
        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_invoice_is_visible_only_to_owner_and_management(): void
    {
        $order = $this->order();
        $url = route('orders.invoice', $order);
        $this->get($url)->assertRedirect(route('login'));
        $this->actingAs(User::factory()->buyer()->create())->get($url)->assertNotFound();
        $this->actingAs($order->user)->get($url)->assertOk()->assertSeeText($order->order_number);
        $this->actingAs(User::factory()->admin()->create())->get($url)->assertOk();
    }
}
