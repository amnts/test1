<?php

namespace Tests\Feature;

use App\Models\{Client, Order, Tariff};
use App\Services\Tariffs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use DateTime;
use DateInterval;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * @var \App\Models\Client
     */
    private $client;

    /**
     * @var \App\Models\Tariff
     */
    private $tariff;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = Client::factory()->make();
        $this->tariff = Tariff::first();
    }

    public function test_order_can_be_created()
    {
        $response = $this->postJson('/orders', array_merge([
            'tariff_id' => $this->tariff->id,
            'delivery_date_start' => $this->getAvailableDateForTariff(),
        ], $this->client->only('name', 'phone')));

        $response->assertStatus(200);
        $this->assertEquals(Order::count(), 1);
        $response->assertJsonStructure([
            'id',
        ]);
    }

    public function test_order_fields_required()
    {
        $response = $this->postJson('/orders');

        $response->assertInvalid([
            'name', 'phone', 'tariff_id', 'delivery_date_start',
        ]);
    }

    public function test_delivery_day_checked()
    {
        $response = $this->postJson('/orders', array_merge([
            'tariff_id' => $this->tariff->id,
            'delivery_date_start' => $this->getInvalidDateForTariff(),
        ], $this->client->only('name', 'phone')));

        $response->assertInvalid([
            'delivery_date_start',
        ]);
    }

    public function test_client_is_not_duplicated_after_order()
    {
        $this->client->save();

        $response = $this->postJson('/orders', array_merge([
            'tariff_id' => $this->tariff->id,
            'delivery_date_start' => $this->getAvailableDateForTariff(),
        ], $this->client->only('name', 'phone')));

        $response->assertStatus(200);
        $this->assertEquals(Order::count(), 1);
        $this->assertEquals(Client::where('phone', $this->client->phone)->count(), 1);
    }

    private function getAvailableDateForTariff()
    {
        $tariffService = app(Tariffs::class);
        $date = new DateTime;
        $interval = new DateInterval('P1D');

        for ($shift = 0; $shift < 7; $shift++) {
            if ($tariffService->isDateValidForTariff($date, $this->tariff)) {
                return $date->format('Y-m-d');
            }

            $date->add($interval);
        }

        throw new \Exception('Tariff does not provide valid delivery date');
    }

    private function getInvalidDateForTariff()
    {
        $tariffService = app(Tariffs::class);
        $date = new DateTime;
        $interval = new DateInterval('P1D');

        for ($shift = 0; $shift < 7; $shift++) {
            if (!$tariffService->isDateValidForTariff($date, $this->tariff)) {
                return $date->format('Y-m-d');
            }

            $date->add($interval);
        }

        throw new \Exception('All dates are valid for selected tariff');
    }
}
