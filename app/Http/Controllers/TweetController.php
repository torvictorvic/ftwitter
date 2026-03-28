<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTweetRequest;
use App\Models\Tweet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    public function store(StoreTweetRequest $request): RedirectResponse
    {
        $request->user()->tweets()->create($request->validated());

        return back()->with('success', 'Tweet created.');
    }

    public function destroy(Request $request, Tweet $tweet): RedirectResponse
    {
        abort_unless($request->user()->id === $tweet->user_id, 403);

        $tweet->delete();

        return back()->with('success', 'Tweet deleted.');
    }
}
