<?php

namespace Tests\Feature\Models;

use App\Models\Model;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_user_can_create_models()
    {
        $user = User::factory()->create();
        $category = Model::factory()->raw();

        Sanctum::actingAs($user);

        $this->jsonApi()->withData([
            'type' => 'models',
            'attributes' => $category,
        ])->post(route('api.v1.models.create'))
            ->assertCreated();;

        $this->assertDatabaseHas('models',[
            'name' => $category['name'],
            'slug' => $category['slug'],
        ]);

    }

}
