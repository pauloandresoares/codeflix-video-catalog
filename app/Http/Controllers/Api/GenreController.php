<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends BasicCrudController
{

    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean',
        'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL'
    ];

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());

        $self = $this;
        return \DB::transaction(function () use($self, $request, $validatedData){
            $genre = $this->model()::create($validatedData);
            $self->handleRelations($genre, $request);
            $genre->refresh();
            $resource = $this->resource();
            return new $resource($genre);
        });
    }

    public function update(Request $request, $id)
    {
        $genre = $this->findOrFail($id);

        $validatedData = $this->validate($request, $this->rulesUpdate());

        $self = $this;
        return \DB::transaction(function () use($self, $request, $genre, $validatedData){
            $genre->update($validatedData);
            $self->handleRelations($genre, $request);
            $resource = $this->resource();
            return new $resource($genre);
        });


    }

    protected function handleRelations($genre, Request $request)
    {
        $genre->categories()->sync($request->post('categories_id'));
    }

    protected function model()
    {
        return Genre::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }

    protected function resource()
    {
        return GenreResource::class;
    }

    protected function resourceCollection()
    {
        return $this->resource();
    }
}
