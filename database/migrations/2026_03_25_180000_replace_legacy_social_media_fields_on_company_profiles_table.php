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
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('x_url')->nullable()->after('facebook_url');
        });

        DB::table('company_profiles')
            ->whereNotNull('twitter_url')
            ->update([
                'x_url' => DB::raw('twitter_url'),
            ]);

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn(['twitter_url', 'rss_url', 'google_plus_url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('twitter_url')->nullable()->after('facebook_url');
            $table->string('rss_url')->nullable()->after('instagram_url');
            $table->string('google_plus_url')->nullable()->after('rss_url');
        });

        DB::table('company_profiles')
            ->whereNotNull('x_url')
            ->update([
                'twitter_url' => DB::raw('x_url'),
            ]);

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn('x_url');
        });
    }
};
