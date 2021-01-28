<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post as PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
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
        return PostResource::collection(
            Post::all()
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
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image_id' => ['nullable', 'integer', 'exists:images,id'],
            'title' => ['required', 'string', 'max:255', 'unique:posts'],
            'body' => ['required', 'string'],
            'published' => ['required', 'boolean'],
        ]);

        return new PostResource(
            Post::create([
                'category_id' => $request->category_id,
                'user_id' => $request->user()->id,
                'image_id' => $request->image_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'body' => $request->body,
                'published' => $request->published,
            ])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $input = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image_id' => ['nullable', 'integer', 'exists:images,id'],
            'title' => ['filled', 'string', 'max:255', Rule::unique('posts')->ignore($post)],
            'body' => ['filled', 'string'],
            'published' => ['boolean'],
        ]);

        if ($request->filled('title')) {
            $input['slug'] = Str::slug($request->title);
        }

        $post->update($input);

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response(null, 204);
    }
}
