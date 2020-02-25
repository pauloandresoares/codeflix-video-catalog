<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

/**
 * Class CategoryTest
 * @package Tests\Unit\Models
 * @group unit
 * @group Category
 * @group CategoryModel
 */
class CategoryTest extends TestCase
{
    /**
     * @var Category
     */
    private $category;

    private $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = new Category();
    }

    public function testIfExtendsModelFromEloquent()
    {
        $this->assertInstanceOf(Model::class, $this->category);
    }

    public function testIfUseTraits()
    {
        $traits = [SoftDeletes::class, Uuid::class];
        $categoryTraits = array_keys(class_uses(Category::class));

        $this->assertEquals($traits, $categoryTraits);
    }

    public function testFillable()
    {
        $this->assertEquals($this->fillable, $this->category->getFillable());
    }

    public function testDatesAttribute()
    {
        $dates = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($dates as $date) {
            $this->assertContains($date, $this->category->getDates());
        }
        $this->assertCount(count($dates), $this->category->getDates());
    }

    public function testCastsAttribute()
    {
        $casts = [
            'id' => 'string',
            'is_active' => 'boolean'
        ];
        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->category->incrementing);
    }
}
