<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show(Request $request, User $user): Response
    {
        $viewer = $request->user();

        $tweets = Tweet::query()
            ->with('user')
            ->withCount('likes')
            ->withExists([
                'likes as is_liked' => fn ($query) => $query->where('user_id', $viewer->id),
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(function (Tweet $tweet) use ($viewer) {
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
            });

        return Inertia::render('Profile/Show', [
            'profileUser' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'bio' => $user->bio,
                'initials' => $user->initials(),
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
                'is_following' => $viewer->id !== $user->id
                    ? $viewer->isFollowing($user)
                    : false,
                'is_self' => $viewer->id === $user->id,
            ],
            'tweets' => $tweets,
            'followers' => $user->followers()
                ->orderBy('name')
                ->get()
                ->map(fn (User $follower) => [
                    'id' => $follower->id,
                    'name' => $follower->name,
                    'username' => $follower->username,
                    'bio' => $follower->bio,
                    'initials' => $follower->initials(),
                ])
                ->values(),
            'following' => $user->following()
                ->orderBy('name')
                ->get()
                ->map(fn (User $followed) => [
                    'id' => $followed->id,
                    'name' => $followed->name,
                    'username' => $followed->username,
                    'bio' => $followed->bio,
                    'initials' => $followed->initials(),
                ])
                ->values(),
        ]);
    }
}