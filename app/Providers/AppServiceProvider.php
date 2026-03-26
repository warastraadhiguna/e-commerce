<?php

namespace App\Providers;

use App\Models\CompanyProfile;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('partner-registrations', function (Request $request): Limit {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function () use ($request) {
                    return redirect()
                        ->to(route('contact').'#partner-registration')
                        ->withInput($request->except('website'))
                        ->withErrors([
                            'full_name' => 'Terlalu banyak percobaan. Coba lagi dalam beberapa saat.',
                        ]);
                });
        });

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
