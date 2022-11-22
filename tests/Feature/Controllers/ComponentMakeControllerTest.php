<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ComponentMake;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentMakeControllerTest extends TestCase
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
    public function it_displays_index_view_with_component_makes()
    {
        $componentMakes = ComponentMake::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('component-makes.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.component_makes.index')
            ->assertViewHas('componentMakes');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_component_make()
    {
        $response = $this->get(route('component-makes.create'));

        $response->assertOk()->assertViewIs('app.component_makes.create');
    }

    /**
     * @test
     */
    public function it_stores_the_component_make()
    {
        $data = ComponentMake::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('component-makes.store'), $data);

        $this->assertDatabaseHas('component_makes', $data);

        $componentMake = ComponentMake::latest('id')->first();

        $response->assertRedirect(
            route('component-makes.edit', $componentMake)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_component_make()
    {
        $componentMake = ComponentMake::factory()->create();

        $response = $this->get(route('component-makes.show', $componentMake));

        $response
            ->assertOk()
            ->assertViewIs('app.component_makes.show')
            ->assertViewHas('componentMake');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_component_make()
    {
        $componentMake = ComponentMake::factory()->create();

        $response = $this->get(route('component-makes.edit', $componentMake));

        $response
            ->assertOk()
            ->assertViewIs('app.component_makes.edit')
            ->assertViewHas('componentMake');
    }

    /**
     * @test
     */
    public function it_updates_the_component_make()
    {
        $componentMake = ComponentMake::factory()->create();

        $data = [
            'component_make' => $this->faker->text(255),
        ];

        $response = $this->put(
            route('component-makes.update', $componentMake),
            $data
        );

        $data['id'] = $componentMake->id;

        $this->assertDatabaseHas('component_makes', $data);

        $response->assertRedirect(
            route('component-makes.edit', $componentMake)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_component_make()
    {
        $componentMake = ComponentMake::factory()->create();

        $response = $this->delete(
            route('component-makes.destroy', $componentMake)
        );

        $response->assertRedirect(route('component-makes.index'));

        $this->assertSoftDeleted($componentMake);
    }
}
