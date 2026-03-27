<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountPasswordRequest;
use App\Http\Requests\UpdateAccountProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccountController extends Controller
{
    public function show(Request $request): View
    {
        $user = $this->ensureShopper($request);
        $accountOrders = $user->orders()
            ->with('items')
            ->paginate(10, ['*'], 'orders_page')
            ->appends(['tab' => 'orders']);

        return view('account.show', [
            'accountUser' => $user->load('shippingAddresses'),
            'accountOrders' => $accountOrders,
        ]);
    }

    public function updateProfile(UpdateAccountProfileRequest $request): RedirectResponse
    {
        $user = $this->ensureShopper($request);

        $user->update($request->validated());

        return redirect()
            ->route('account.show', ['tab' => 'profile'])
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Profil diperbarui',
                'text' => 'Data akun kamu berhasil disimpan.',
            ]);
    }

    public function updatePassword(UpdateAccountPasswordRequest $request): RedirectResponse
    {
        $user = $this->ensureShopper($request);

        $user->update([
            'password' => $request->string('password')->toString(),
        ]);

        return redirect()
            ->route('account.show', ['tab' => 'password'])
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Password diperbarui',
                'text' => 'Password akun kamu berhasil diganti.',
            ]);
    }

    protected function ensureShopper(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $user->isShopper()) {
            throw new HttpException(403, 'Halaman akun hanya tersedia untuk buyer dan mitra.');
        }

        return $user;
    }
}
