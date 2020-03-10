<?php

namespace Tests\Unit\Models;

use App\Models\Traits\Uuid;
use App\Models\Video;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

/**
 * Class GenreTest
 * @package Tests\Unit\Models
 * @group unit
 * @group Video
 * @group VideoModel
 */
class VideoUnitTest extends TestCase
{
    private $video;

    public function testFillableAttribute()
    {
        $fillable = [
            'title',
            'description',
            'year_launched',
            'opened',
            'rating',
            'duration'
        ];
        $this->assertEquals($fillable, $this->video->getFillable());
    }

    public function testCastsAttribute()
    {
        $casts = [
            'id' => 'string',
            'opened' => 'boolean',
            'year_launched' => 'integer',
            'duration' => 'integer'
        ];
        $this->assertEquals($casts, $this->video->getCasts());
    }

    public function testIncrementsAttribute()
    {
        $this->assertFalse($this->video->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ["deleted_at", "created_at", "updated_at"];
        $videoDates = $this->video->getDates();
        $this->assertEqualsCanonicalizing($dates, $videoDates);
    }

    public function testIfUseTraits()
    {
        $traits = [
            Uuid::class,
            SoftDeletes::class,
        ];
        $videoTraits = array_values(class_uses(Video::class));

        $this->assertEquals($traits, $videoTraits);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = new Video();
    }
}
