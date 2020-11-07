<?php

namespace Tests\Feature\Cars;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateCarsTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_update_cars()
    {
        $car = Car::factory()->create();

        $this->jsonApi()
            ->patch(route('api.v1.cars.update',$car))
            ->assertStatus(401);

    }

    /** @test */
    public function registered_users_can_update_their_cars()
    {

        $car = Car::factory()->create();

        Sanctum::actingAs($car->user);

        $this->jsonApi()->withData([
            'type'=>'cars',
            'id'=> $car->getRouteKey(),
            'attributes'=>[
                'brand' => 'Brand changed',
                'slug' => 'brand-changed',
                'description' => 'Description changed',
            ]
        ])
            ->patch(route('api.v1.cars.update',$car))
            ->assertStatus(Response::HTTP_OK); //200

        $this->assertDatabaseHas('cars',[
            'brand' => 'Brand changed',
            'slug' => 'brand-changed',
            'description' => 'Description changed',
        ]);
    }

    /** @test */
    public function registered_users_cannot_update_other_cars()
    {
        $car = Car::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->withData([
            'type'=>'cars',
            'id'=> $car->getRouteKey(),
            'attributes'=>[
                'brand' => 'Brand changed',
                'slug' => 'brand-changed',
                'description' => 'Description changed',
            ]
        ])->patch(route('api.v1.cars.update',$car))
            ->assertStatus(Response::HTTP_FORBIDDEN); //403

        $this->assertDatabaseMissing('cars',[
            'brand' => 'Brand changed',
            'slug' => 'brand-changed',
            'description' => 'Description changed',
        ]);
    }

    /** @test */
    public function can_update_only_brand()
    {
        $car = Car::factory()->create();
        Sanctum::actingAs($car->user);

        $this->jsonApi()
            ->withData([
            'type'=>'cars',
            'id'=> $car->getRouteKey(),
            'attributes'=>[
                'brand' => 'Brand changed',
            ]
        ])->patch(route('api.v1.cars.update',$car))
            ->assertStatus(Response::HTTP_OK); //200

        $this->assertDatabaseHas('cars',[
            'brand' => 'Brand changed',
        ]);
    }

    /** @test */
    public function can_update_only_slug()
    {
        $car = Car::factory()->create();
        Sanctum::actingAs($car->user);

        $this->jsonApi()
            ->withData([
                'type'=>'cars',
                'id'=> $car->getRouteKey(),
                'attributes'=>[
                    'slug' => 'slug-changed',
                ]
            ])->patch(route('api.v1.cars.update',$car))
            ->assertStatus(Response::HTTP_OK); //200

        $this->assertDatabaseHas('cars',[
            'slug' => 'slug-changed',
        ]);
    }


    /** @test */
    public function can_update_only_description()
    {
        $car = Car::factory()->create();
        Sanctum::actingAs($car->user);

        $this->jsonApi()
            ->withData([
                'type'=>'cars',
                'id'=> $car->getRouteKey(),
                'attributes'=>[
                    'description' => 'description changed',
                ]
            ])->patch(route('api.v1.cars.update',$car))
            ->assertStatus(Response::HTTP_OK); //200

        $this->assertDatabaseHas('cars',[
            'description' => 'description changed',
        ]);
    }
}
