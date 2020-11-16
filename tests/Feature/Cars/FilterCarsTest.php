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

        $url = route('api.v1.articles.index',['filter[title]'=> 'Laravel']);
    }
}
