<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function favoritesTotal()
    {
        return [
            'total' => auth()->user()->favorites()->count(),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function favorites(Request $request)
    {
        $this->validate($request, [
            'search' => 'string',
            'sort' => 'string',
        ]);

        $query = auth()->user()->favorites();

        if ($request->has('search')) {
            $query = $query->where(function ($query) use ($request) {
                $query = $query
                    ->orWhere(
                        'user_name',
                        'like',
                        '%'.$request->search.'%'
                    )
                    ->orWhere(
                        'message',
                        'like',
                        '%'.$request->search.'%'
                    );
            });
        }

        $order = 'asc';
        $column = $request->get('sort', '-published_at');

        if (strpos($column, '-') === 0) {
            $order = 'desc';
            $column = substr($column, 1);
        }

        $query = $query->orderBy($column, $order);

        return PostResource::collection($query->paginate());
    }

    /**
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function favorite(Post $post)
    {
        auth()->user()->favorites()->attach($post->id);
    }

    /**
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function unfavorite(Post $post)
    {
        auth()->user()->favorites()->detach($post->id);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'string',
            'tags' => 'string',
            'sort' => 'string',
        ]);

        $query = Post::query();

        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);

            $query = $query->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query = $query->where(
                        'message',
                        'like',
                        '%'.$tag.'%'
                    );
                }
            });
        }

        if ($request->has('search')) {
            $query = $query->where(function ($query) use ($request) {
                $query = $query
                    ->orWhere(
                        'user_name',
                        'like',
                        '%'.$request->search.'%'
                    )
                    ->orWhere(
                        'message',
                        'like',
                        '%'.$request->search.'%'
                    );
            });
        }

        $order = 'asc';
        $column = $request->get('sort', '-published_at');

        if (strpos($column, '-') === 0) {
            $order = 'desc';
            $column = substr($column, 1);
        }

        $query = $query->orderBy($column, $order);

        return PostResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
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
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
