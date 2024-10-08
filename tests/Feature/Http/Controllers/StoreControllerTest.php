<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_unauthenticated_user_is_bounced_to_login(): void
    {
        $response = $this->get(route('stores.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_autenticated_user_can_see_stores(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('stores.index'));

        $response->assertStatus(200)
            ->assertSee('Stores');
    }

    public function test_store_creation_screen_can_be_viewed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('stores.create'));

        $response->assertStatus(200)
            ->assertSee('New Store');
    }

    public function test_a_store_can_be_added(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('stores.store', [
                'name' => $this->faker->company,
                'open' => $this->faker->boolean(75),
                'type' => $this->faker->numberBetween(1, 3),
                'max_delivery_distance' => $this->faker->numberBetween(5, 500),
                'latitude' => $this->faker->numberBetween(1, 30) . '.00',
                'longitude' => $this->faker->numberBetween(1, 30) . '.00',
            ]));

        $response->assertStatus(302)
            ->assertRedirect(route('stores.index'));
    }

    public function test_a_store_cannot_be_added_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('stores.store', [
                'name' => null,
                'open' => $this->faker->boolean(75),
                'type' => $this->faker->numberBetween(1, 3),
                'max_delivery_distance' => $this->faker->numberBetween(5, 500),
                'latitude' => $this->faker->numberBetween(1, 30) . '.00',
                'longitude' => $this->faker->numberBetween(1, 30) . '.00',
            ]));

        $response->assertStatus(302)
            ->assertInvalid('name');
    }

    public function test_store_edit_screen_can_be_viewed(): void
    {
        $user = User::factory()->create();

        $storeData = [
            'name' => $this->faker->company,
            'open' => $this->faker->boolean(75),
            'type' => $this->faker->numberBetween(1, 3),
            'max_delivery_distance' => $this->faker->numberBetween(5, 500),
            'latitude' => $this->faker->numberBetween(1, 30) . '.00',
            'longitude' => $this->faker->numberBetween(1, 30) . '.00',
        ];

        $this->actingAs($user)
            ->post(route('stores.store', $storeData));

        $createdStore = Store::where('name', $storeData['name'])
            ->first();

        $response = $this->actingAs($user)
            ->get(route('stores.edit', ['store' => $createdStore]));

        $response->assertStatus(200)
            ->assertSee('Update Store');
    }

    public function test_store_can_be_updated(): void
    {
        $user = User::factory()->create();

        $storeData = [
            'name' => $this->faker->company,
            'open' => $this->faker->boolean(75),
            'type' => $this->faker->numberBetween(1, 3),
            'max_delivery_distance' => $this->faker->numberBetween(5, 500),
            'latitude' => $this->faker->numberBetween(1, 30) . '.00',
            'longitude' => $this->faker->numberBetween(1, 30) . '.00',
        ];

        $this->actingAs($user)
            ->post(route('stores.store', $storeData));

        $createdStore = Store::where('name', $storeData['name'])
            ->first();

        $newStoreData = [
            'name' => $this->faker->company,
            'open' => $this->faker->boolean(75),
            'type' => $this->faker->numberBetween(1, 3),
            'max_delivery_distance' => $this->faker->numberBetween(5, 500),
            'latitude' => $this->faker->numberBetween(1, 30) . '.00',
            'longitude' => $this->faker->numberBetween(1, 30) . '.00',
        ];

        $response = $this->actingAs($user)
            ->put(route('stores.update', ['store' => $createdStore]), $newStoreData);

        $response->assertStatus(302)
            ->assertRedirect(route('stores.index'));

        $updatedStore = Store::where('name', $newStoreData['name'])
            ->first();

        $this->assertNotEquals($createdStore->name, $updatedStore->name);
    }

    public function test_a_store_cannot_be_updated_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $storeData = [
            'name' => $this->faker->company,
            'open' => $this->faker->boolean(75),
            'type' => $this->faker->numberBetween(1, 3),
            'max_delivery_distance' => $this->faker->numberBetween(5, 500),
            'latitude' => $this->faker->numberBetween(1, 30) . '.00',
            'longitude' => $this->faker->numberBetween(1, 30) . '.00',
        ];

        $this->actingAs($user)
            ->post(route('stores.store', $storeData));

        $createdStore = Store::where('name', $storeData['name'])
            ->first();

        $newStoreData = [
            'name' => null,
            'open' => $this->faker->boolean(75),
            'type' => $this->faker->numberBetween(1, 3),
            'max_delivery_distance' => $this->faker->numberBetween(5, 500),
            'latitude' => $this->faker->numberBetween(1, 30) . '.00',
            'longitude' => $this->faker->numberBetween(1, 30) . '.00',
        ];

        $response = $this->actingAs($user)
            ->put(route('stores.update', ['store' => $createdStore]), $newStoreData);

        $response->assertStatus(302)
            ->assertInvalid('name');
    }
}
