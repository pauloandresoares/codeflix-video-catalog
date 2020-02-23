<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class Category extends Model
{

    use SoftDeletes, Uuid;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $dates = ['deleted_at'];

    /** @var array força cast para exibir models, ao usar toArray*/
    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];
}
