<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_profiles', function (Blueprint $table): void {
            $table->string('theme_primary_color', 7)->default('#fed700');
            $table->string('theme_secondary_color', 7)->default('#0363cd');
            $table->string('theme_background_color', 7)->default('#ffffff');
            $table->string('theme_font', 30)->default('default');
            $table->string('theme_pattern_path')->nullable();
            $table->string('favicon_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('company_profiles', fn (Blueprint $table) => $table->dropColumn([
            'theme_primary_color', 'theme_secondary_color', 'theme_background_color',
            'theme_font', 'theme_pattern_path', 'favicon_path',
        ]));
    }
};
