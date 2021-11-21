<?php

namespace App\Http\Controllers;

use App\Services\Tariffs;

class IndexController extends Controller
{
    public function __invoke(Tariffs $tariffsService) {
        return view('index', [
            'tariffs' => $tariffsService->getSortedWithDeliveryDays(),
        ]);
    }
}
