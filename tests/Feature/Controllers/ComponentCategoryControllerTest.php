<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\ComponentCategory;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentCategoryControllerTest extends TestCase
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
    public function it_displays_index_view_with_component_categories()
    {
        $componentCategories = ComponentCategory::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('component-categories.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.component_categories.index')
            ->assertViewHas('componentCategories');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_component_category()
    {
        $response = $this->get(route('component-categories.create'));

        $response->assertOk()->assertViewIs('app.component_categories.create');
    }

    /**
     * @test
     */
    public function it_stores_the_component_category()
    {
        $data = ComponentCategory::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('component-categories.store'), $data);

        $this->assertDatabaseHas('component_categories', $data);

        $componentCategory = ComponentCategory::latest('id')->first();

        $response->assertRedirect(
            route('component-categories.edit', $componentCategory)
        );
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_component_category()
    {
        $componentCategory = ComponentCategory::factory()->create();

        $response = $this->get(
            route('component-categories.show', $componentCategory)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.component_categories.show')
            ->assertViewHas('componentCategory');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_component_category()
    {
        $componentCategory = ComponentCategory::factory()->create();

        $response = $this->get(
            route('component-categories.edit', $componentCategory)
        );

        $response
            ->assertOk()
            ->assertViewIs('app.component_categories.edit')
            ->assertViewHas('componentCategory');
    }

    /**
     * @test
     */
    public function it_updates_the_component_category()
    {
        $componentCategory = ComponentCategory::factory()->create();

        $data = [
            'component_category' => $this->faker->text(255),
        ];

        $response = $this->put(
            route('component-categories.update', $componentCategory),
            $data
        );

        $data['id'] = $componentCategory->id;

        $this->assertDatabaseHas('component_categories', $data);

        $response->assertRedirect(
            route('component-categories.edit', $componentCategory)
        );
    }

    /**
     * @test
     */
    public function it_deletes_the_component_category()
    {
        $componentCategory = ComponentCategory::factory()->create();

        $response = $this->delete(
            route('component-categories.destroy', $componentCategory)
        );

        $response->assertRedirect(route('component-categories.index'));

        $this->assertSoftDeleted($componentCategory);
    }
}
