<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShippingAddressRequest;
use App\Models\ShippingAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ShippingAddressController extends Controller
{
    public function store(StoreShippingAddressRequest $request): RedirectResponse
    {
        $user = $this->ensureShopper($request);
        $data = $request->validated();

        DB::transaction(function () use ($user, $data): void {
            $isDefault = (bool) ($data['is_default'] ?? false);

            if ($isDefault || $user->shippingAddresses()->count() === 0) {
                $user->shippingAddresses()->update(['is_default' => false]);
                $data['is_default'] = true;
            }

            $data['sort_order'] = ((int) $user->shippingAddresses()->max('sort_order')) + 1;

            $user->shippingAddresses()->create($data);
        });

        return redirect()
            ->route('account.show', ['tab' => 'shipping'])
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Alamat ditambahkan',
                'text' => 'Alamat kirim baru berhasil ditambahkan.',
            ]);
    }

    public function update(StoreShippingAddressRequest $request, ShippingAddress $shippingAddress): RedirectResponse
    {
        $user = $this->ensureShopper($request);
        $this->ensureOwnership($user->getKey(), $shippingAddress);

        $data = $request->validated();

        DB::transaction(function () use ($user, $shippingAddress, $data): void {
            $isDefault = (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                $user->shippingAddresses()
                    ->whereKeyNot($shippingAddress->getKey())
                    ->update(['is_default' => false]);
            } elseif (! $user->shippingAddresses()->whereKeyNot($shippingAddress->getKey())->where('is_default', true)->exists()) {
                $data['is_default'] = true;
            }

            $shippingAddress->update($data);
        });

        return redirect()
            ->route('account.show', ['tab' => 'shipping'])
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Alamat diperbarui',
                'text' => 'Alamat kirim berhasil diperbarui.',
            ]);
    }

    public function destroy(Request $request, ShippingAddress $shippingAddress): RedirectResponse
    {
        $user = $this->ensureShopper($request);
        $this->ensureOwnership($user->getKey(), $shippingAddress);

        DB::transaction(function () use ($user, $shippingAddress): void {
            $wasDefault = $shippingAddress->is_default;

            $shippingAddress->delete();

            if ($wasDefault) {
                $user->shippingAddresses()
                    ->where('is_default', false)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->limit(1)
                    ->update(['is_default' => true]);
            }
        });

        return redirect()
            ->route('account.show', ['tab' => 'shipping'])
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Alamat dihapus',
                'text' => 'Alamat kirim berhasil dihapus.',
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

    protected function ensureOwnership(int $userId, ShippingAddress $shippingAddress): void
    {
        if ($shippingAddress->user_id !== $userId) {
            throw new HttpException(404);
        }
    }
}
