<?php

namespace Tests\Feature\Cars;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DeleteCarsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_cannot_delete_cars()
    {
        $car = Car::factory()->create();

        $this->jsonApi()
            ->delete(route('api.v1.cars.delete',$car))
            ->assertStatus(Response::HTTP_UNAUTHORIZED); //401
    }

    /** @test */
    public function authenticated_users_can_delete_their_cars()
    {
        $car = Car::factory()->create();

        Sanctum::actingAs($car->user);

        $this->jsonApi()
            ->delete(route('api.v1.cars.delete',$car))
            ->assertStatus(Response::HTTP_NO_CONTENT); //204
    }

    /** @test */
    public function authenticated_users_cannot_delete_other_cars()
    {
        $car = Car::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()
            ->delete(route('api.v1.cars.delete',$car))
            ->assertStatus(Response::HTTP_FORBIDDEN); //403
    }

}
