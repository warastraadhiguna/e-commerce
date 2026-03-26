<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnerRegistrationRequest;
use App\Models\PartnerRegistration;

class PartnerRegistrationController extends Controller
{
    public function __invoke(StorePartnerRegistrationRequest $request)
    {
        PartnerRegistration::query()->create($request->sanitized());

        return redirect()
            ->to(route('contact').'#partner-registration')
            ->with('partner_registration_success', 'Pendaftaran mitra berhasil dikirim. Tim kami akan segera menindaklanjuti.');
    }
}
