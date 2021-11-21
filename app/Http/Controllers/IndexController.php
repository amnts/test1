<?php

namespace App\Http\Controllers;

use App\Models\Tariff;

class IndexController extends Controller
{
    public function __invoke() {
        return view('index', [
            'tariffs' => Tariff::query()
                ->with(['deliveryDays' => function($query) {
                    $query->orderBy('index');
                }])
                ->orderBy('index')
                ->get(),
        ]);
    }
}
