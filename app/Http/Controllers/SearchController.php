<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $viewer = $request->user();
        $term = trim((string) $request->string('q')->toString());

        $followedIds = $viewer->following()->pluck('users.id')->all();

        $users = User::query()
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($nested) use ($term) {
                    $nested->where('name', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%");
                });
            })
            ->where('id', '!=', $viewer->id)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString()
            ->through(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'bio' => $user->bio,
                'initials' => $user->initials(),
                'is_following' => in_array($user->id, $followedIds, true),
            ]);

        return Inertia::render('Search/Index', [
            'filters' => [
                'q' => $term,
            ],
            'users' => $users,
        ]);
    }
}