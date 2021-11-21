<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDay extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'tariff_id', 'week_day',
    ];
}
