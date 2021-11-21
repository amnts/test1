<?php

namespace App\Services;

use App\Models\Tariff;

class Tariffs
{
    public function getSortedWithDeliveryDays()
    {
        return Tariff::select('id', 'title', 'price')
            ->with(['deliveryDays' => function($query) {
                $query->select('id', 'week_day', 'tariff_id')
                    ->orderBy('index');
            }])
            ->orderBy('index')
            ->get()
            ->keyBy('id');
    }
}
