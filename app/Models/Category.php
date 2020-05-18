<?php

namespace App\Models;

use App\ModelFilters\CategoryFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class Category extends Model
{

    use SoftDeletes, Uuid, Filterable;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

        protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function modelFilter()
    {
        return $this->provideFilter(CategoryFilter::class);
    }
}
