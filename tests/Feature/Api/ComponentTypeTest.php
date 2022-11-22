<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ComponentType;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentTypeTest extends TestCase
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
    public function it_gets_component_types_list()
    {
        $componentTypes = ComponentType::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.component-types.index'));

        $response->assertOk()->assertSee($componentTypes[0]->component_type);
    }

    /**
     * @test
     */
    public function it_stores_the_component_type()
    {
        $data = ComponentType::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.component-types.store'), $data);

        $this->assertDatabaseHas('component_types', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.component-types.update', $componentType),
            $data
        );

        $data['id'] = $componentType->id;

        $this->assertDatabaseHas('component_types', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_component_type()
    {
        $componentType = ComponentType::factory()->create();

        $response = $this->deleteJson(
            route('api.component-types.destroy', $componentType)
        );

        $this->assertSoftDeleted($componentType);

        $response->assertNoContent();
    }
}
