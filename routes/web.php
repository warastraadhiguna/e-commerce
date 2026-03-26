<?php

use App\Http\Controllers\PartnerRegistrationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact/register', PartnerRegistrationController::class)
    ->middleware('throttle:partner-registrations')
    ->name('partner-registrations.store');
