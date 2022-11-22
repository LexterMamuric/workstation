<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ComponentModel;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_component_models_list()
    {
        $componentModels = ComponentModel::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.component-models.index'));

        $response->assertOk()->assertSee($componentModels[0]->component_model);
    }

    /**
     * @test
     */
    public function it_stores_the_component_model()
    {
        $data = ComponentModel::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.component-models.store'), $data);

        $this->assertDatabaseHas('component_models', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_component_model()
    {
        $componentModel = ComponentModel::factory()->create();

        $data = [
            'component_model' => $this->faker->text(255),
        ];

        $response = $this->putJson(
            route('api.component-models.update', $componentModel),
            $data
        );

        $data['id'] = $componentModel->id;

        $this->assertDatabaseHas('component_models', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_component_model()
    {
        $componentModel = ComponentModel::factory()->create();

        $response = $this->deleteJson(
            route('api.component-models.destroy', $componentModel)
        );

        $this->assertSoftDeleted($componentModel);

        $response->assertNoContent();
    }
}
