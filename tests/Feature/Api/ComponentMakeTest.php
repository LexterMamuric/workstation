<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\ComponentMake;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComponentMakeTest extends TestCase
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
    public function it_gets_component_makes_list()
    {
        $componentMakes = ComponentMake::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.component-makes.index'));

        $response->assertOk()->assertSee($componentMakes[0]->component_make);
    }

    /**
     * @test
     */
    public function it_stores_the_component_make()
    {
        $data = ComponentMake::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.component-makes.store'), $data);

        $this->assertDatabaseHas('component_makes', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
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

        $response = $this->putJson(
            route('api.component-makes.update', $componentMake),
            $data
        );

        $data['id'] = $componentMake->id;

        $this->assertDatabaseHas('component_makes', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_component_make()
    {
        $componentMake = ComponentMake::factory()->create();

        $response = $this->deleteJson(
            route('api.component-makes.destroy', $componentMake)
        );

        $this->assertSoftDeleted($componentMake);

        $response->assertNoContent();
    }
}
