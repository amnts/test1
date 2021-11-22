<?php

namespace App\Services;

use App\Models\Tariff;
use DateTime;

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

    public function isDateValidForTariff(DateTime $date, Tariff $tariff)
    {
        $weekDay = $date->format('N');

        foreach ($tariff->deliveryDays as $day) {
            if ($day->week_day == $weekDay) {
                return true;
            }
        }

        return false;
    }
}
