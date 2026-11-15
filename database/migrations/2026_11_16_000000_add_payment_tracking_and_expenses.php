<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('payment_status')->default('unpaid')->index();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable()->index();
        });

        Schema::create('expenses', function (Blueprint $table): void {
            $table->id();
            $table->string('reference')->unique();
            $table->string('description');
            $table->string('category');
            $table->decimal('amount', 18, 2);
            $table->date('expense_date')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::table('orders', fn (Blueprint $table) => $table->dropColumn(['payment_status', 'payment_method', 'payment_reference', 'paid_at']));
    }
};
