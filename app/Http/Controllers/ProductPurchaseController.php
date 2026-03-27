<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductPurchaseController extends Controller
{
    public function __invoke(string $slug): View
    {
        $product = Product::query()
            ->with([
                'brand',
                'productType',
                'category.parent',
                'measurementUnit',
            ])
            ->activePublished()
            ->where('slug', $slug)
            ->firstOrFail();

        $user = request()->user();

        if (! $user || $user->isManagementUser()) {
            throw new HttpException(403, 'Akun admin/superadmin tidak digunakan untuk pembelian publik.');
        }

        return view('product-purchase', [
            'product' => $product,
            'buyer' => $user,
        ]);
    }
}
