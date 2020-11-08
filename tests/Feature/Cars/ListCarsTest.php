<?php

namespace Tests\Feature\Cars;

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListCarsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_cars()
    {
        $car = Car::factory()->create();
        $response = $this->jsonApi()->get(route('api.v1.cars.read', $car));
        $response->assertJson([
            'data' => [
                'type' => 'cars',
                'id' => (string)$car->getRouteKey(),
                'attributes' => [
                    'brand' => $car->brand,
                    'slug' => $car->slug,
                    'description' => $car->description,
                    'createdAt' => $car->created_at->toAtomString(),
                    'updatedAt' => $car->updated_at->toAtomString(),
                ],
                'links' => [
                    'self' => route('api.v1.cars.read', $car)
                ]
            ]
        ]);
        $this->assertNull(
            $response->json('data.relationships.authors.data'),
            "The key 'data.relationships.authors.data' must be null"
        );
    }

    /** @test */
    public function can_fetch_all_cars()
    {
        $car = Car::factory()->times(3)->create();
        $response = $this->jsonApi()->get(route('api.v1.cars.index'));
        $response->assertJson([
            'data' => [
                [
                    'type' => 'cars',
                    'id' => (string)$car[0]->getRouteKey(),
                    'attributes' => [
                        'brand' => $car[0]->brand,
                        'slug' => $car[0]->slug,
                        'description' => $car[0]->description,
                        'createdAt' => $car[0]->created_at->toAtomString(),
                        'updatedAt' => $car[0]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.cars.read', $car[0])
                    ]
                ],
                [
                    'type' => 'cars',
                    'id' => (string)$car[1]->getRouteKey(),
                    'attributes' => [
                        'brand' => $car[1]->brand,
                        'slug' => $car[1]->slug,
                        'description' => $car[1]->description,
                        'createdAt' => $car[1]->created_at->toAtomString(),
                        'updatedAt' => $car[1]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.cars.read', $car[1])
                    ]
                ],
                [
                    'type' => 'cars',
                    'id' => (string)$car[2]->getRouteKey(),
                    'attributes' => [
                        'brand' => $car[2]->brand,
                        'slug' => $car[2]->slug,
                        'description' => $car[2]->description,
                        'createdAt' => $car[2]->created_at->toAtomString(),
                        'updatedAt' => $car[2]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.cars.read', $car[2])
                    ]
                ],
            ]
        ]);
    }
}
