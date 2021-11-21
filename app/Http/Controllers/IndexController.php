<?php

namespace App\Http\Controllers;

use App\Models\Tariff;

class IndexController extends Controller
{
    public function __invoke() {
        return view('index', [
            'tariffs' => Tariff::select('id', 'title', 'price')
                ->with(['deliveryDays' => function($query) {
                    $query->select('id', 'week_day', 'tariff_id')
                        ->orderBy('index');
                }])
                ->orderBy('index')
                ->get()
                ->keyBy('id'),
        ]);
    }
}
