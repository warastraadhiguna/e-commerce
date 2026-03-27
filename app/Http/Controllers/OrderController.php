<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Request $request, Order $order): View
    {
        abort_unless($request->user()?->isShopper(), 403, 'Halaman pesanan hanya untuk buyer atau mitra.');
        abort_unless($order->user_id === $request->user()?->getKey(), 404);

        $order->load([
            'items',
        ]);

        return view('order-show', [
            'order' => $order,
        ]);
    }
}
