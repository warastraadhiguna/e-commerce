<?php

use App\Enums\PartnerDiscountStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('phone');
            $table->string('contact_person')->nullable()->after('business_name');
            $table->text('address')->nullable()->after('contact_person');
            $table->decimal('purchase_turnover_total', 15, 2)->default(0)->after('is_active');
            $table->decimal('minimum_turnover_for_discount', 15, 2)->default(0)->after('purchase_turnover_total');
            $table->string('partner_discount_status')->default(PartnerDiscountStatus::Pending->value)->after('minimum_turnover_for_discount');
            $table->timestamp('partner_discount_approved_at')->nullable()->after('partner_discount_status');
            $table->foreignId('partner_discount_approved_by')->nullable()->after('partner_discount_approved_at')->constrained('users')->nullOnDelete();
            $table->text('partner_discount_notes')->nullable()->after('partner_discount_approved_by');

            $table->index('partner_discount_status');
        });

        if (Schema::hasTable('partner_profiles')) {
            $partnerProfiles = DB::table('partner_profiles')->get();

            foreach ($partnerProfiles as $profile) {
                DB::table('users')
                    ->where('id', $profile->user_id)
                    ->update([
                        'phone' => DB::raw("COALESCE(phone, '".str_replace("'", "''", (string) $profile->phone)."')"),
                        'business_name' => $profile->business_name,
                        'contact_person' => $profile->contact_person,
                        'address' => $profile->address,
                        'purchase_turnover_total' => $profile->purchase_turnover_total,
                        'minimum_turnover_for_discount' => $profile->minimum_turnover_for_discount,
                        'partner_discount_status' => $profile->discount_status,
                        'partner_discount_approved_at' => $profile->discount_approved_at,
                        'partner_discount_approved_by' => $profile->discount_approved_by,
                        'partner_discount_notes' => $profile->discount_notes,
                    ]);
            }

            Schema::dropIfExists('partner_profiles');
        }
    }

    public function down(): void
    {
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

        $mitraUsers = DB::table('users')
            ->whereNotNull('business_name')
            ->orWhereNotNull('contact_person')
            ->orWhereNotNull('address')
            ->orWhere('purchase_turnover_total', '>', 0)
            ->orWhere('minimum_turnover_for_discount', '>', 0)
            ->orWhereNotNull('partner_discount_notes')
            ->get();

        foreach ($mitraUsers as $user) {
            DB::table('partner_profiles')->insert([
                'user_id' => $user->id,
                'business_name' => $user->business_name,
                'contact_person' => $user->contact_person,
                'phone' => $user->phone,
                'address' => $user->address,
                'purchase_turnover_total' => $user->purchase_turnover_total,
                'minimum_turnover_for_discount' => $user->minimum_turnover_for_discount,
                'discount_status' => $user->partner_discount_status,
                'discount_approved_at' => $user->partner_discount_approved_at,
                'discount_approved_by' => $user->partner_discount_approved_by,
                'discount_notes' => $user->partner_discount_notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['partner_discount_approved_by']);
            $table->dropIndex(['partner_discount_status']);
            $table->dropColumn([
                'business_name',
                'contact_person',
                'address',
                'purchase_turnover_total',
                'minimum_turnover_for_discount',
                'partner_discount_status',
                'partner_discount_approved_at',
                'partner_discount_approved_by',
                'partner_discount_notes',
            ]);
        });
    }
};
