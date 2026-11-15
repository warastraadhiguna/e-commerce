<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __invoke(Request $request, Order $order): View
    {
        abort_unless($request->user()?->isManagementUser() || $order->user_id === $request->user()?->id, 404);

        return view('invoice', ['order' => $order->load('items')]);
    }
}
