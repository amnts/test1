<?php

namespace App\Services;

class Orders
{
    private $clientsService;

    public function __construct(Clients $clientsService)
    {
        $this->clientsService = $clientsService;
    }

    public function createOrder(array $data): int
    {
        $client = $this->clientsService->findByPhoneOrCreate($data['phone'], $data);

        $order = $client->orders()->create($data);

        return $order->id;
    }
}
