<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Image as ImageResource;
use App\Models\Category;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;

class ImageController extends Controller
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
        return ImageResource::collection(
            Image::all()
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
            'image' => ['required', 'file', 'image', 'max:51200'],
        ]);

        $path = $request->file('image')->storePublicly('images', [
            'disk' => 'public',
        ]);

        return new ImageResource(
            Image::create([
                'name' => $request->file('image')->getClientOriginalName(),
                'path' => $path,
            ])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return new ImageResource($image);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        $input = $request->validate([
            'name' => ['filled', 'string'],
            'published' => ['boolean'],
        ]);

        $image->update($input);

        return new ImageResource($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        Post::where('image_id', $image->id)->update([
            'image_id' => null,
        ]);

        Category::where('image_id', $image->id)->update([
            'image_id' => null,
        ]);

        $image->delete();

        return response(null, 204);
    }
}
