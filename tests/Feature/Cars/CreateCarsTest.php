<?php

namespace Tests\Feature\Cars;

use App\Models\Car;
use App\Models\Model;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateCarsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_user_cannot_create_a_car()
    {
        $car = array_filter(Car::factory()->raw([
            'user_id'=>null,
        ]));

        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car,
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(401); //Not authorized

        $this->assertDatabaseMissing('cars', $car);

    }

    /** @test */
    public function registered_user_can_create_a_car()
    {
        $user = User::factory()->create();
        $model = Model::factory()->create();;

        $car = array_filter(Car::factory()->raw([
            'model_id'=>null,
        ]));

        Sanctum::actingAs($user);


        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car,
            'relationships' => [
                'models' => [
                    'data' => [
                        'id' => $model->getRouteKey(),
                        'type' => 'models'
                    ]
                ]
            ]
        ])->post(route('api.v1.cars.create'))
            ->assertCreated();

        $this->assertDatabaseHas('cars', [
            'user_id' => $user->id,
            'brand' => $car['brand'],
            'slug' => $car['slug'],
            'description' => $car['description'],
        ]);

    }

    /**  @test */
    public function model_is_required()
    {
        $car = Car::factory()->raw(['model_id' => null]);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertJsonFragment(['source' => ['pointer' => '/data']]);
        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function model_must_be_a_relationship_object()
    {
        $car = Car::factory()->raw(['model_id' => null]);
        $car['models'] = 'slug';

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/models');
        ;
        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function brand_is_required()
    {
        $car = Car::factory()->raw(['brand' => '']);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/brand');

        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function description_is_required()
    {
        $car = Car::factory()->raw(['description' => '']);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/description');

        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function slug_is_required()
    {
        $car = Car::factory()->raw(['slug' => '']);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function slug_must_be_unique()
    {
        Car::factory()->create(['slug' => 'same-slug']);
        $car = Car::factory()->raw(['slug' => 'same-slug']);
        Sanctum::actingAs(User::factory()->create());
        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function slug_must_only_contains_letters_numbers_and_dashes()
    {
        $car = Car::factory()->raw(['slug' => '^$%^#']);

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function slug_must_not_contains_underscores()
    {

        $car = Car::factory()->raw(['slug' => 'with_underscores']);

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee(trans('validation.no_underscores',['attribute'=> 'slug']))
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function slug_must_not_start_with_dashes()
    {

        $car = Car::factory()->raw(['slug' => '-start-with-dash']);

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee(trans('validation.no_starting_dashes',['attribute'=> 'slug']))
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('cars', $car);
    }

    /**  @test */
    public function slug_must_not_end_with_dashes()
    {

        $car = Car::factory()->raw(['slug' => 'end-with-dash-']);

        Sanctum::actingAs(User::factory()->create());

        $this->jsonApi()->withData([
            'type' => 'cars',
            'attributes' => $car
        ])->post(route('api.v1.cars.create'))
            ->assertStatus(422)
            ->assertSee(trans('validation.no_ending_dashes',['attribute'=> 'slug']))
            ->assertSee('data\/attributes\/slug');

        $this->assertDatabaseMissing('cars', $car);
    }

}
