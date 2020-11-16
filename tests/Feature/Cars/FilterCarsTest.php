<?php

namespace Tests\Feature\Cars;

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterCarsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_cars_by_brand()
    {
        Car::factory()->create([
            'brand' => 'Volkswagen'
        ]);

        Car::factory()->create([
            'brand' => 'Ford'
        ]);

        $url = route('api.v1.cars.index',['filter[brand]'=> 'Volkswagen']);

        $this->jsonApi()->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Volkswagen')
            ->assertDontSee('Ford');
    }

    /** @test */
    public function can_filter_cars_by_year()
    {
         Car::factory()->create(['year'=>2014]);
         Car::factory()->create(['year'=>2010]);

        $url = route('api.v1.cars.index',['filter[year]'=> 2014]);

        $this->jsonApi()->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('2014')
            ->assertDontSee('2012');
    }

    /** @test */
    public function can_filter_cars_by_vin()
    {
        Car::factory()->create(['vin'=>'1234567890qwertyu']);
        Car::factory()->create(['vin'=>'qwertyuiop1234567']);

        $url = route('api.v1.cars.index',['filter[vin]'=> '1234567890qwertyu']);

        $this->jsonApi()->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('1234567890qwertyu')
            ->assertDontSee('qwertyuiop1234567');
    }

    /** @test */
    public function can_filter_articles_by_description()
    {
        Car::factory()->create([
            'description' => '<div>Import your car</div>'
        ]);

        Car::factory()->create([
            'description' => '<div>Other text</div>'
        ]);

        $url = route('api.v1.cars.index', ['filter[description]' => 'Import']);

        $this->jsonApi()->get($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Import your car')
            ->assertDontSee('Other text');
    }

}
