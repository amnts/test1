<?php

namespace App\Http\Controllers;

use App\Services\Orders;
use App\Http\Requests\OrderCreateRequest;

class OrderCreateController extends Controller
{
    public function __invoke(OrderCreateRequest $request, Orders $ordersService)
    {
        return response()->json([
            'id' => $ordersService->createOrder($request->getPreparedData()),
        ]);
    }
}
