<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    private $rules = [
        'name' => 'required|max:255',
        'is_active' => 'boolean'
    ];

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        return  Category::create($request->all());
    }

    /**
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) // Route Model Binding
    {
        return $category;
    }


    /**
     * @param Request $request
     * @param Category $category
     * @return Category
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, $this->rules);
        $category->update($request->all());
        return $category;
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent(); //204
    }
}
