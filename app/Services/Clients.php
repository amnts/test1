<?php

namespace App\Services;

use App\Models\Client;

class Clients
{
    public function findByPhoneOrCreate(string $phone, array $data)
    {
        return Client::updateOrCreate([
            'phone' => $phone,
        ], $data);
    }
}
