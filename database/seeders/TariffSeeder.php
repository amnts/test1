<?php

namespace Database\Seeders;

use App\Models\Tariff;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getTariffs() as $id => $tariffData) {
            $insertData = collect($tariffData)->except('days')->merge([
                'index' => $id,
            ])->toArray();

            $tariff = Tariff::updateOrCreate([
                'id' => $id,
            ], $insertData);

            foreach ($tariffData['days'] as $day) {
                $tariff->deliveryDays()->updateOrCreate([
                    'week_day' => $day,
                ]);
            }
        }
    }

    private function getTariffs()
    {
        return [
            1 => [
                'title' => 'Тариф 1',
                'price' => '100',
                'days'  => [1, 2, 5, 6],
            ],
            2 => [
                'title' => 'Тариф 2',
                'price' => '200',
                'days'  => [1, 5],
            ],
            3 => [
                'title' => 'Тариф 3',
                'price' => '300',
                'days'  => [1, 3, 5],
            ],
        ];
    }
}
