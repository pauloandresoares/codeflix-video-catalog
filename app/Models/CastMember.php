<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use Uuid, SoftDeletes;

    const TYPE_DIRECTOR = 1;
    const TYPE_ACTOR = 2;

    protected $fillable = [
        'name',
        'type'
    ];

    public static $typesList = [
        self::TYPE_DIRECTOR,
        self::TYPE_ACTOR
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'id' => 'string',
        'type' => 'integer'
    ];

    public $incrementing = false;
}
