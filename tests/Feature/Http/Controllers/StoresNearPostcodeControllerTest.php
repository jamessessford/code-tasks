<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Postcode;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoresNearPostcodeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        Http::fake([
            'www.doogal.co.uk/*' => Http::response(Storage::disk('support')->get('postcodes.zip'), 200),
        ]);

        $this->artisan('postcodes');
    }

    public function test_controller_can_be_called(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $response = $this->get('api/stores-near-postcode/54.857658/-1.5756/5');

        $response->assertStatus(200);
    }

    public function test_we_can_find_stores_near_to_postcode(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $postcode = Postcode::where('postcode', 'DH3 3EX')->first();

        $store = Store::factory()->count(1)->create([
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
        ])->first();

        $response = $this->get('api/stores-near-postcode/54.857658/-1.5756/5');

        $response->assertStatus(200)
            ->assertSee($store->name);
    }

    public function test_we_can_modify_the_distance_and_lose_stores(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $postcode = Postcode::where('postcode', 'DH3 3EX')->first();

        $store = Store::factory()->count(1)->create([
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
        ])->first();

        $response = $this->get('api/stores-near-postcode/54.857658/-1.5756/-1');

        $response->assertStatus(200)
            ->assertDontSee($store->name);
    }

    public function test_stores_outside_our_boundary_arent_picked_up(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $postcode = Postcode::where('postcode', '!=', 'DH3 3EX')->first();

        $store = Store::factory()->count(1)->create([
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
        ])->first();

        $response = $this->get('api/stores-near-postcode/54.857658/-1.5756/-1');

        $response->assertStatus(200)
            ->assertDontSee($store->name);
    }

    public function test_we_can_modify_distance_and_find_stores_outside_our_area(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $postcode = Postcode::where('postcode', '!=', 'DH3 3EX')->first();

        $store = Store::factory()->count(1)->create([
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
        ])->first();

        $response = $this->get('api/stores-near-postcode/54.857658/-1.5756/500');

        $response->assertStatus(200)
            ->assertSee($store->name);
    }
}
