<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table): void {
            $table->text('short_description')->nullable()->after('map_embed_url');
            $table->longText('long_description')->nullable()->after('short_description');
        });

        DB::table('company_profiles')
            ->whereNull('short_description')
            ->update([
                'short_description' => DB::raw('description'),
            ]);

        DB::table('company_profiles')
            ->whereNull('long_description')
            ->update([
                'long_description' => DB::raw('description'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table): void {
            $table->dropColumn(['short_description', 'long_description']);
        });
    }
};
