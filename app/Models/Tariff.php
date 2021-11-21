<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use HasFactory;

    public $fillable = [
        'title', 'price',
    ];

    public function deliveryDays()
    {
        return $this->hasMany(DeliveryDay::class);
    }
}
