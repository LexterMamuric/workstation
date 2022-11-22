<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ComponentCategory;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentCategoryTest extends TestCase
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
    public function it_gets_component_categories_list()
    {
        $componentCategories = ComponentCategory::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.component-categories.index'));

        $response
            ->assertOk()
            ->assertSee($componentCategories[0]->component_category);
    }

    /**
     * @test
     */
    public function it_stores_the_component_category()
    {
        $data = ComponentCategory::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(
            route('api.component-categories.store'),
            $data
        );

        $this->assertDatabaseHas('component_categories', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.component-categories.update', $componentCategory),
            $data
        );

        $data['id'] = $componentCategory->id;

        $this->assertDatabaseHas('component_categories', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_component_category()
    {
        $componentCategory = ComponentCategory::factory()->create();

        $response = $this->deleteJson(
            route('api.component-categories.destroy', $componentCategory)
        );

        $this->assertSoftDeleted($componentCategory);

        $response->assertNoContent();
    }
}
