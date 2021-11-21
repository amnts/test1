<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_index_is_working()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
