<?php

namespace App\Http\Controllers;

use App\Actions\Timeline\GetTimelineTweets;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(Request $request, GetTimelineTweets $timeline): Response
    {
        $tweets = $timeline->handle($request->user());

        return Inertia::render('Home', [
            'tweets' => $tweets->through(
                fn (Tweet $tweet) => $this->serializeTweet($tweet, $request->user())
            ),
        ]);
    }

    private function serializeTweet(Tweet $tweet, User $viewer): array
    {
        return [
            'id' => $tweet->id,
            'body' => $tweet->body,
            'likes_count' => $tweet->likes_count,
            'is_liked' => (bool) $tweet->is_liked,
            'can_delete' => $viewer->id === $tweet->user_id,
            'created_at' => $tweet->created_at->toIso8601String(),
            'created_at_human' => $tweet->created_at->diffForHumans(),
            'author' => [
                'name' => $tweet->user->name,
                'username' => $tweet->user->username,
                'bio' => $tweet->user->bio,
                'initials' => $tweet->user->initials(),
            ],
        ];
    }
}