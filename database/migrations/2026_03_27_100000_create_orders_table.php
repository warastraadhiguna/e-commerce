<?php

use App\Enums\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('shipping_address_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default(OrderStatus::Pending->value)->index();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_role', 30);
            $table->boolean('uses_partner_pricing')->default(false);
            $table->string('shipping_label')->nullable();
            $table->string('shipping_recipient_name');
            $table->string('shipping_phone')->nullable();
            $table->text('shipping_address_line');
            $table->string('shipping_district')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_province')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->text('shipping_notes')->nullable();
            $table->unsignedInteger('item_count')->default(0);
            $table->decimal('total_quantity', 12, 3)->default(0);
            $table->decimal('subtotal_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->text('user_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamp('checked_out_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
