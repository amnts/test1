<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $fillable = [
        'client_id', 'tariff_id', 'delivery_date_start',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
