<?php

use App\Enums\PartnerRegistrationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partner_registrations', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name', 120);
            $table->string('whatsapp', 30);
            $table->text('message')->nullable();
            $table->string('status', 20)->default(PartnerRegistrationStatus::Pending->value);
            $table->text('admin_notes')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_registrations');
    }
};
