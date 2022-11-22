<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ComponentModel;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentModelControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_component_models()
    {
        $componentModels = ComponentModel::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('component-models.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.component_models.index')
            ->assertViewHas('componentModels');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_component_model()
    {
        $response = $this->get(route('component-models.create'));

        $response->assertOk()->assertViewIs('app.component_models.create');
    }

    /**
     * @test
     */
    public function it_stores_the_component_model()
    {
        $data = ComponentModel::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('component-models.store'), $data);

        $this->assertDatabaseHas('component_models', $data);

        $componentModel = ComponentModel::latest('id')->first();

        $response->assertRedirect(
            route('component-models.edit', $componentModel)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_component_model()
    {
        $componentModel = ComponentModel::factory()->create();

        $response = $this->get(route('component-models.show', $componentModel));

        $response
            ->assertOk()
            ->assertViewIs('app.component_models.show')
            ->assertViewHas('componentModel');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_component_model()
    {
        $componentModel = ComponentModel::factory()->create();

        $response = $this->get(route('component-models.edit', $componentModel));

        $response
            ->assertOk()
            ->assertViewIs('app.component_models.edit')
            ->assertViewHas('componentModel');
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

        $response = $this->put(
            route('component-models.update', $componentModel),
            $data
        );

        $data['id'] = $componentModel->id;

        $this->assertDatabaseHas('component_models', $data);

        $response->assertRedirect(
            route('component-models.edit', $componentModel)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_component_model()
    {
        $componentModel = ComponentModel::factory()->create();

        $response = $this->delete(
            route('component-models.destroy', $componentModel)
        );

        $response->assertRedirect(route('component-models.index'));

        $this->assertSoftDeleted($componentModel);
    }
}
