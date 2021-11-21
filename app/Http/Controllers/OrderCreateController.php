<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\OrderCreateRequest;

class OrderCreateController extends Controller
{
    public function __invoke(OrderCreateRequest $request)
    {
        $data = $request->validated();

        $client = Client::firstOrCreate([
            'phone' => $data['phone'],
        ], $data);

        $order = $client->orders()->create($data);

        return response()->json([
            'id' => $order->id,
        ]);
    }
}
