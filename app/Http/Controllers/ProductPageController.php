<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class ProductPageController extends Controller
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

        $relatedProducts = Product::query()
            ->with([
                'brand:id,name,slug',
                'productType:id,name,slug',
                'category:id,name,slug,parent_id',
                'measurementUnit:id,name,symbol',
            ])
            ->activePublished()
            ->whereKeyNot($product->getKey())
            ->where(function ($query) use ($product): void {
                $query
                    ->where('product_category_id', $product->product_category_id)
                    ->orWhere('brand_id', $product->brand_id)
                    ->orWhere('product_type_id', $product->product_type_id);
            })
            ->orderByRaw("
                CASE
                    WHEN product_category_id = ? THEN 0
                    WHEN brand_id = ? THEN 1
                    WHEN product_type_id = ? THEN 2
                    ELSE 3
                END
            ", [
                $product->product_category_id,
                $product->brand_id,
                $product->product_type_id,
            ])
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        return view('product-show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
