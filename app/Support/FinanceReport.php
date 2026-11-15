<?php

namespace App\Support;

use App\Enums\OrderStatus;
use App\Models\Expense;
use App\Models\Order;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class FinanceReport
{
    public function forMonth(string $month): array
    {
        $start = CarbonImmutable::createFromFormat('!Y-m', $month)->startOfMonth();
        $end = $start->addMonth();
        $orders = Order::query()->where('payment_status', 'paid')
            ->where('paid_at', '>=', $start)->where('paid_at', '<', $end)->get();
        $expenses = Expense::query()->whereDate('expense_date', '>=', $start->toDateString())
            ->whereDate('expense_date', '<', $end->toDateString())->get();
        $income = (float) $orders->sum('total_amount');
        $outgoing = (float) $expenses->sum('amount');

        $entries = $orders->map(fn (Order $order): array => [
            'date' => $order->paid_at->format('Y-m-d'),
            'reference' => $order->order_number,
            'description' => 'Pembayaran '.$order->customer_name,
            'type' => 'Pemasukan',
            'amount' => (float) $order->total_amount,
        ])->concat($expenses->map(fn (Expense $expense): array => [
            'date' => $expense->expense_date->format('Y-m-d'),
            'reference' => $expense->reference,
            'description' => $expense->description,
            'type' => 'Pengeluaran',
            'amount' => (float) $expense->amount,
        ]))->sortByDesc('date')->values();

        return [
            'month_label' => $start->locale('id')->translatedFormat('F Y'),
            'income' => $income,
            'expenses' => $outgoing,
            'balance' => $income - $outgoing,
            'paid_count' => $orders->count(),
            'receivables' => (float) Order::query()->where('payment_status', 'unpaid')
                ->where('status', '!=', OrderStatus::Cancelled->value)->sum('total_amount'),
            'entries' => $entries,
            'daily' => Collection::times($start->daysInMonth, function (int $day) use ($start, $entries): array {
                $date = $start->day($day)->format('Y-m-d');
                $lines = $entries->where('date', $date);

                return ['day' => $day, 'income' => $lines->where('type', 'Pemasukan')->sum('amount'), 'expense' => $lines->where('type', 'Pengeluaran')->sum('amount')];
            }),
        ];
    }
}
