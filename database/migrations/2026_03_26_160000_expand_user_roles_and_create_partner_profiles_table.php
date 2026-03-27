<?php

use App\Enums\PartnerDiscountStatus;
use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
            $table->index('is_active');
        });

        DB::table('users')
            ->where('role', 'customer')
            ->update(['role' => UserRole::Buyer->value]);

        Schema::create('partner_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('business_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->decimal('purchase_turnover_total', 15, 2)->default(0);
            $table->decimal('minimum_turnover_for_discount', 15, 2)->default(0);
            $table->string('discount_status')->default(PartnerDiscountStatus::Pending->value);
            $table->timestamp('discount_approved_at')->nullable();
            $table->foreignId('discount_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('discount_notes')->nullable();
            $table->timestamps();

            $table->index('discount_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_profiles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropColumn('is_active');
        });
    }
};
