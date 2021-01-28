<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(
            'index',
            'show'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(
            Category::withCount('posts')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image_id' => ['nullable', 'integer', 'exists:images,id'],
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'published' => ['required', 'boolean'],
        ]);

        return new CategoryResource(
            Category::create([
                'image_id' => $request->image_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'published' => $request->published,
            ])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $input = $request->validate([
            'image_id' => ['nullable', 'integer', 'exists:images,id'],
            'name' => ['filled', 'string', 'max:255', Rule::unique('categories')->ignore($category)],
            'published' => ['boolean'],
        ]);

        if ($request->filled('name')) {
            $input['slug'] = Str::slug($request->name);
        }

        $category->update($input);

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        Post::where('category_id', $category->id)->update([
            'category_id' => null,
        ]);

        $category->delete();

        return response(null, 204);
    }
}
