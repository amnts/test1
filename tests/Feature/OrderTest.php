<?php

namespace Tests\Feature;

use App\Models\{Client, Order};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    
    public function test_order_can_be_created()
    {
        $response = $this->post('/orders', [
            'name' => 'Михаил',
            'phone' => '79000000001',
            'tariff_id' => 1,
            'delivery_day_id' => 1,
        ]);

        $response->assertStatus(200);

        $this->assertEquals(Order::count(), 1);

        $response->assertJsonStructure([
            'id',
        ]);
    }

    public function test_order_fields_required()
    {
        $response = $this->post('/orders');

        $response->assertInvalid([
            'name', 'phone', 'tariff_id', 'delivery_day_id',
        ]);
    }

    public function test_client_is_not_duplicated_after_order()
    {
        $client = Client::create([
            'name' => 'Михаил',
            'phone' => '79000000001',
        ]);

        $response = $this->post('/orders', [
            'name' => $client->name,
            'phone' => $client->phone,
            'tariff_id' => 1,
            'delivery_day_id' => 1,
        ]);

        $response->assertStatus(200);

        $this->assertEquals(Order::count(), 1);

        $this->assertEquals(Client::where('phone', $client->phone)->count(), 1);
    }
}
