<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

/**
 * Class CategoryTest
 * @package Tests\Unit\Models
 * @group unit
 * @group CastMember
 * @group CastMemberModel
 */
class CastMemberUnitTest extends TestCase
{
    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();

        $this->castMember = new CastMember();
    }

    public function testIfUseTraits()
    {
        $traits = [
            Uuid::class,
            SoftDeletes::class
        ];

        $castMemberTraits = array_keys(class_uses(CastMember::class));

        $this->assertEquals($traits, $castMemberTraits);
    }

    public function testFillableAttribute()
    {
        $fillable = [
            'name',
            'type'
        ];

        $this->assertEquals($fillable, $this->castMember->getFillable());
    }

    public function testDatesAttribute()
    {
        $castMemberDates = $this->castMember->getDates();
        $dates = [
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        foreach ($dates as $date) {
            $this->assertContains($date, $castMemberDates);
        }

        $this->assertCount(count($dates), $castMemberDates);
    }

    public function testCastsAttribute()
    {
        $casts = [
            'id' => 'string',
            'type' => 'integer'
        ];

        $this->assertEquals($casts, $this->castMember->getCasts());
    }

    public function testIncrementingAttribute()
    {

        $this->assertFalse($this->castMember->getIncrementing());
    }
}
