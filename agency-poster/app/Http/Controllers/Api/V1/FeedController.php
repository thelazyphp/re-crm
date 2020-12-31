<?php

namespace App\Http\Controllers\Api\V1;

use App\Feed;
use App\CrmObject;
use App\FeedGenerateStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Feed::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function show(Feed $feed)
    {
        return $feed;
    }

    /**
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function generate(Feed $feed)
    {
        $crmObjects = CrmObject::where('disabled', false)
            // ->where('category', 'flat')
            // ->where('region', 'Брест')
            // ->take(15)
            ->get();

        $feedGenerateStatus = FeedGenerateStatus::create([
            'user_id' => Auth::id(),
            'feed_id' => $feed->id,
            'total' => count($crmObjects),
        ]);

        $feedGenerateStatus->errors = [];
        $feedGenerateStatus->save();

        $response = json_encode(['status' => true]);

        ignore_user_abort(true);
        ob_start();

        echo $response;

        $contentLocation = config('app.url')
            .'/api/v1/feeds/'
            .$feed->id
            .'/generate-statuses/'
            .$feedGenerateStatus->id;

        header('Connection: close', true, 202);
        header('Content-Type: application/json');
        header('Content-Length: '.strlen($response));
        header('Content-Location: '.$contentLocation);

        ob_end_flush();
        ob_flush();
        flush();

        if (session_id()) {
            session_write_close();
        }

        set_time_limit(0);

        $xml = view("stubs.feeds.{$feed->id}", compact('feedGenerateStatus', 'crmObjects'))->render();

        $path = "feeds/{$feed->id}.xml";

        Storage::disk('public')->put($path, $xml);

        $feed->url = Storage::disk('public')->url($path);
        $feed->storage_path = $path;
        $feed->generated_at = date($feed->getDateFormat());

        $feed->save();

        $feedGenerateStatus->status = FeedGenerateStatus::GENERATED_STATUS;
        $feedGenerateStatus->save();
    }
}
