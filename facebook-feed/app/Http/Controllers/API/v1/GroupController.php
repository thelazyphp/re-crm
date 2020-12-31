<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Group;
use Illuminate\Http\Request;
use App\Parsing\Parser;
use App\Http\Resources\GroupResource;
use App\Post;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|string',
        ]);

		$url = $request->url;

		if (is_numeric($url)) {
			$url = 'https://facebook.com/groups/'.$url;
		}

        $parser = new Parser();
        $group = $parser->searchGroup($url);

        if (
            $group === false
            || empty($group['id'])
            || empty($group['url'])
            || empty($group['is_public'])
            || empty($group['is_visible'])
            || empty($group['name'])
        ) {
            $data = [
                'status' => false,
                'message' => 'Group is not found!',
            ];

            return response()->json($data, 404);
        }

        return $group;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function total()
    {
        return [
            'total' => Group::count(),
        ];
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
        ]);

        $query = Group::query();

        if ($request->has('search')) {
            $query = $query->where(
                'name',
                'like',
                '%'.$request->search.'%'
            );
        }

        $order = 'asc';
        $column = $request->get('sort', '-updated_at');

        if (strpos($column, '-') === 0) {
            $order = 'desc';
            $column = substr($column, 1);
        }

        $query = $query->orderBy($column, $order);

        return GroupResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|string|numeric|unique:groups',
            'url' => 'required|string|max:191|url|unique:groups',
            'is_public' => 'required|boolean',
            'is_visible' => 'required|boolean',
            'image' => 'nullable|string',
            'name' => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'get_posts' => 'boolean',
            'posts_limit' => 'integer|min:1|max:500',
        ]);

        return new GroupResource(
            Group::create(
                $request->only([
                    'id',
                    'url',
                    'is_public',
                    'is_visible',
                    'image',
                    'name',
                    'description',
                    'get_posts',
                    'posts_limit',
                ])
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $this->validate($request, [
            'get_posts' => 'boolean',
            'posts_limit' => 'integer|min:1|max:500',
        ]);

        if ($request->has('get_posts')) {
            $group->get_posts = $request->get_posts;
        }

        if ($request->has('posts_limit')) {
            $group->posts_limit = $request->posts_limit;
        }

        $group->save();

        return new GroupResource($group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $id = $group->id;
        $group->delete();
        Post::where('group_id', $id)->delete();
    }
}
