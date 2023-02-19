<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\GoalType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FeedController extends AccountController
{
    public function index()
    {
        $feeds = $this->feedRepository->getFeedByUserId($this->user->id, $this->company->id);

        if ($feeds) {
            foreach ($feeds as $feed) {
                if ($feed->weekly_status) {
                    $feed->weekly_status->comments_count = $feed->weekly_status->comments()->count();
                    unset($feed->weekly_status->comments);
                } elseif ($feed->goal) {
                    $feed->goal->comments_count = $feed->goal->comments()->count();
                    unset($feed->goal->comments);
                } elseif ($feed->key_result) {
                    $feed->key_result->comments_count = $feed->key_result->comments()->count();
                    unset($feed->key_result->comments);
                }
            }

            return response()->json([
                'success' => true,
                'feeds' => $feeds,
            ], Response::HTTP_OK);
        }

        return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    public function feedsCount()
    {
        return response()->json([
            'success' => true,
            'count' => $this->feedRepository->getFeedByUserIdCount($this->user->id, $this->company->id),
        ], Response::HTTP_OK);
    }

    public function changeStatus($id)
    {
        $feed = $this->feedRepository->getFeedById($id, $this->company->id);

        if (!$feed) {
            return self::httpBadRequest(self::NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        $feed->read_at = Carbon::now();
        if ($feed->save()) {
            return response()->json([
                'success' => true,
            ], Response::HTTP_OK);
        }
    }

}
