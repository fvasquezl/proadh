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
                    'title' => $car->title,
                    'slug' => $car->slug,
                    'excerpt' => $car->excerpt,
                    'publishedAt' => $car->published_at->toAtomString(),
                    'createdAt' => $car->created_at->toAtomString(),
                    'updatedAt' => $car->updated_at->toAtomString(),
                ],
                'links' => [
                    'self' => route('api.v1.cars.read', $car)
                ]
            ]
        ]);
//        $this->assertNull(
//            $response->json('data.relationships.authors.data'),
//            "The key 'data.relationships.authors.data' must be null"
//        );
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
                        'title' => $car[0]->title,
                        'slug' => $car[0]->slug,
                        'excerpt' => $car[0]->excerpt,
                        'publishedAt' => $car[0]->published_at->toAtomString(),
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
                        'title' => $car[1]->title,
                        'slug' => $car[1]->slug,
                        'excerpt' => $car[1]->excerpt,
                        'publishedAt' => $car[1]->published_at->toAtomString(),
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
                        'title' => $car[2]->title,
                        'slug' => $car[2]->slug,
                        'excerpt' => $car[2]->excerpt,
                        'publishedAt' => $car[2]->published_at->toAtomString(),
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
