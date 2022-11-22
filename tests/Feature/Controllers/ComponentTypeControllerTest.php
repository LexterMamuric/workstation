<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ComponentType;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentTypeControllerTest extends TestCase
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
    public function it_displays_index_view_with_component_types()
    {
        $componentTypes = ComponentType::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('component-types.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.component_types.index')
            ->assertViewHas('componentTypes');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_component_type()
    {
        $response = $this->get(route('component-types.create'));

        $response->assertOk()->assertViewIs('app.component_types.create');
    }

    /**
     * @test
     */
    public function it_stores_the_component_type()
    {
        $data = ComponentType::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('component-types.store'), $data);

        $this->assertDatabaseHas('component_types', $data);

        $componentType = ComponentType::latest('id')->first();

        $response->assertRedirect(
            route('component-types.edit', $componentType)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_component_type()
    {
        $componentType = ComponentType::factory()->create();

        $response = $this->get(route('component-types.show', $componentType));

        $response
            ->assertOk()
            ->assertViewIs('app.component_types.show')
            ->assertViewHas('componentType');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_component_type()
    {
        $componentType = ComponentType::factory()->create();

        $response = $this->get(route('component-types.edit', $componentType));

        $response
            ->assertOk()
            ->assertViewIs('app.component_types.edit')
            ->assertViewHas('componentType');
    }

    /**
     * @test
     */
    public function it_updates_the_component_type()
    {
        $componentType = ComponentType::factory()->create();

        $data = [
            'component_type' => $this->faker->text(255),
        ];

        $response = $this->put(
            route('component-types.update', $componentType),
            $data
        );

        $data['id'] = $componentType->id;

        $this->assertDatabaseHas('component_types', $data);

        $response->assertRedirect(
            route('component-types.edit', $componentType)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_component_type()
    {
        $componentType = ComponentType::factory()->create();

        $response = $this->delete(
            route('component-types.destroy', $componentType)
        );

        $response->assertRedirect(route('component-types.index'));

        $this->assertSoftDeleted($componentType);
    }
}
