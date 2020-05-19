<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends BasicCrudController
{
    /**
     * @var array
     */
    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean',
        'categories_id' => 'required|array|exists:categories,id,deleted_at,NULL',
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
            return new $resource($genre->load('categories'));
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
            return new $resource($genre->load('categories'));
        });
    }

    public function show($id)
    {
        $obj = $this->findOrFail($id);
        $resource = $this->resource();
        return new $resource($obj->load('categories'));
    }

    protected function handleRelations(Genre $genre, Request $request)
    {
        $genre->categories()->sync($request->get('categories_id'));
    }

    protected function model(): string
    {
        return Genre::class;
    }

    protected function rulesStore(): array
    {
        return $this->rules;
    }

    protected function rulesUpdate(): array
    {
        return $this->rules;
    }

    protected function resource(): string
    {
        return GenreResource::class;
    }

    protected function resourceCollection(): string
    {
        return GenreResource::class;
    }
}
