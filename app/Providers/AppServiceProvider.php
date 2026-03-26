<?php

namespace App\Providers;

use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            $companyProfile = once(function () {
                if (! Schema::hasTable('company_profiles')) {
                    return CompanyProfile::make(CompanyProfile::defaultAttributes());
                }

                return CompanyProfile::query()->first()
                    ?? CompanyProfile::make(CompanyProfile::defaultAttributes());
            });

            $view->with('companyProfile', $companyProfile);
        });
    }
}
