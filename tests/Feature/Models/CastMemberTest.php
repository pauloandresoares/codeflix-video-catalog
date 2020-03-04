<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Class CategoryTest
 * @package Tests\Feature\Models
 * @group feature
 * @group CastMember
 * @group CastMemberModel
 */
class CastMemberTest extends TestCase
{

    use DatabaseMigrations;

    public function testList()
    {
        factory(CastMember::class)->create();
        $cast_members = CastMember::all();

        $cast_memberKeys = array_keys($cast_members->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            ['id', 'name', 'type', 'created_at', 'updated_at', 'deleted_at'],
            $cast_memberKeys
        );

        $this->assertCount(1, $cast_members);
    }

    public function testCreateIsDirector()
    {
        $cast_member = CastMember::create(['name' => 'teste2', 'type' => CastMember::TYPE_DIRECTOR]);
        $this->assertEquals(CastMember::TYPE_DIRECTOR, $cast_member->type);
    }

    public function testCreateIsActor()
    {
        $cast_member = CastMember::create(['name' => 'teste2', 'type' => CastMember::TYPE_ACTOR]);
        $this->assertEquals(CastMember::TYPE_ACTOR, $cast_member->type);
    }

    public function testUpdate()
    {
        $cast_member = factory(CastMember::class)->create();
        $data = ['name' => 'test_name_updated', 'type' => CastMember::TYPE_ACTOR];
        $cast_member->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $cast_member->{$key});
        }
    }

    public function testDelete()
    {
        $cast_member = factory(CastMember::class)->create();
        $cast_member->delete();

        $this->assertEquals(0, CastMember::count());
    }

    public function testValidUuid()
    {
        $cast_member = factory(CastMember::class)->create();
        $this->assertTrue(is_string($cast_member->id));
        $this->assertEquals(36, strlen($cast_member->id));
    }

}
