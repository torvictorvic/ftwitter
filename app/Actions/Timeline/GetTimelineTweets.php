<?php

namespace App\Actions\Timeline;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GetTimelineTweets
{
    public function handle(User $viewer, int $perPage = 15): LengthAwarePaginator
    {
        $followedIds = DB::table('follows')
            ->select('followed_id')
            ->where('follower_id', $viewer->id);

        return Tweet::query()
            ->with('user')
            ->withCount('likes')
            ->withExists([
                'likes as is_liked' => fn ($query) => $query->where('user_id', $viewer->id),
            ])
            ->where(function ($query) use ($viewer, $followedIds) {
                $query->where('user_id', $viewer->id)
                    ->orWhereIn('user_id', $followedIds);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}