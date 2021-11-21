<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $fillable = [
        'client_id', 'tariff_id', 'delivery_day',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function setDeliveryDayAttribute($value)
    {
        $day = DeliveryDay::query()
            ->where('tariff_id', $this->attributes['tariff_id'])
            ->where('week_day', $value)
            ->first();

        if ($day) {
            $this->attributes['delivery_day_id'] = $day->id;
        }
    }
}
