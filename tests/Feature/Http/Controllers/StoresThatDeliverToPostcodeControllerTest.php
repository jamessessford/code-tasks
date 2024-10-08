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

class StoresThatDeliverToPostcodeControllerTest extends TestCase
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

        $response = $this->get('api/stores-that-deliver-to-postcode/DH3 3EX');

        $response->assertStatus(200);
    }

    public function test_a_postcode_we_dont_have_will_404(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $response = $this->get('api/stores-that-deliver-to-postcode/DH3 3JF');

        $response->assertStatus(404);
    }

    public function test_a_store_that_will_deliver_to_our_postcode(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $postcode = Postcode::where('postcode', 'DH3 3EX')->first();

        $store = Store::factory()->count(1)->create([
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
            'max_delivery_distance' => 3,
        ])->first();

        $response = $this->get('api/stores-that-deliver-to-postcode/DH3 3EX');

        $response->assertStatus(200)
            ->assertSee($store->name);
    }

    public function test_a_store_that_wont_deliver_to_our_postcode(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $postcode = Postcode::where('postcode', 'DH3 3EX')->first();

        $deliverableStore = Store::factory()->count(1)->create([
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
            'max_delivery_distance' => 3,
        ])->first();

        $postcode = Postcode::where('postcode', '!=', 'DH3 3EX')->first();

        $nonDeliverableStore = Store::factory()->count(1)->create([
            'latitude' => $postcode->latitude,
            'longitude' => $postcode->longitude,
            'max_delivery_distance' => 3,
        ])->first();

        $response = $this->get('api/stores-that-deliver-to-postcode/DH3 3EX');

        $response->assertStatus(200)
            ->assertSee($deliverableStore->name)
            ->assertDontSee($nonDeliverableStore->name);
    }
}
