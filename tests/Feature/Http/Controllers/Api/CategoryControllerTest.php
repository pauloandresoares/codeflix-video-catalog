<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

/**
 * Class CategoryControllerTest
 * @package Tests\Feature\Http\Controllers\Api
 * @group feature
 * @group Category
 * @group CategoryController
 */
class CategoryControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = factory(Category::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('categories.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {
        $category = factory(Category::class)->create();
        $response = $this->get(route('categories.show',['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testStore()
    {
        $data = [
            'name' => 'test'
        ];

        $response = $this->assertStore(
            $data,
            $data + [
                'description' => null,
                'is_active' => true,
                'deleted_at' => null
            ]
        );

        $response->assertJsonStructure([
            'created_at',
            'updated_at'
        ]);

        $data = [
            'name' => 'name test',
            'description' => 'description test',
            'is_active' => false
        ];

        $this->assertStore(
            $data,
            $data + [
                'description' => 'description test',
                'is_active' => false
            ]
        );
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'name test',
            'description' => 'description test',
            'is_active' => true
        ];

        $response = $this->assertUpdate(
            $data,
            $data + [
                'deleted_at' => null
            ]
        );

        $response->assertJsonStructure([
            'created_at',
            'updated_at'
        ]);

        $data = [
            'name' => 'name test',
            'description' => ''
        ];

        $this->assertUpdate(
            $data,
            array_merge(
                $data,
                [
                    'description' => null
                ]
            )
        );

        $data['description'] = 'description test';
        $this->assertUpdate(
            $data,
            array_merge(
                $data,
                [
                    'description' => 'description test'
                ]
            )
        );

        $data['description'] = '';
        $this->assertUpdate(
            $data,
            array_merge(
                $data,
                [
                    'description' => null
                ]
            )
        );
    }


    public function testInvalidationData()
    {
        $data = [
            'name' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256)
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);

        $data = [
            'is_active' => 'a'
        ];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function testDestroy()
    {
        $category = factory(Category::class)->create();
        $response = $this->json('DELETE',route('categories.destroy', ['category' => $category->id]));
        $response->assertStatus(204);
        $this->assertNull(Category::find($category->id));
        $this->assertNotNull(Category::withTrashed()->find($category->id));
    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $this->assertInvalidationFields(
            $response, ['name'], 'required', []
        );
        $response->assertJsonMissingValidationErrors(['is_active']);
    }

    protected function assertInvalidationMax(TestResponse $response)
    {
        $this->assertInvalidationFields(
            $response, ['name'], 'max.string', ['max' => 255]
        );
    }

    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);
    }

    protected function routeStore()
    {
        return route('categories.store');
    }

    protected function routeUpdate()
    {
        return route('categories.update', [
            'category' => $this->category->id
        ]);
    }

    protected function model()
    {
        return Category::class;
    }
}
