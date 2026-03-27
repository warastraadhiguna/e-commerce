<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\WishlistManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistManager $wishlist,
    ) {}

    public function index(): View
    {
        return view('wishlist', [
            'wishlistProducts' => $this->wishlist->products(),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        if ($this->wishlist->contains($product)) {
            return redirect()
                ->to($this->safeRedirectTo($request, route('wishlist.index')))
                ->with('wishlist_feedback', [
                    'icon' => 'info',
                    'title' => 'Sudah ada di wishlist',
                    'text' => 'Produk ini sudah ada di wishlist kamu.',
                ]);
        }

        $this->wishlist->add($product);

        return redirect()
            ->to($this->safeRedirectTo($request, route('wishlist.index')))
            ->with('wishlist_feedback', [
                'icon' => 'success',
                'title' => 'Berhasil ditambahkan',
                'text' => 'Produk sudah masuk ke wishlist kamu.',
            ]);
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $this->wishlist->remove($product);

        return redirect()
            ->to($this->safeRedirectTo($request, route('wishlist.index')))
            ->with('wishlist_feedback', [
                'icon' => 'success',
                'title' => 'Wishlist diperbarui',
                'text' => 'Produk berhasil dihapus dari wishlist.',
            ]);
    }

    protected function safeRedirectTo(Request $request, string $fallback): string
    {
        $redirect = trim((string) $request->input('redirect_to', ''));

        if ($redirect === '' || ! str_starts_with($redirect, '/') || str_starts_with($redirect, '//')) {
            return $fallback;
        }

        return $redirect;
    }
}
