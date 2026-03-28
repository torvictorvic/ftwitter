<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, Tweet $tweet): RedirectResponse
    {
        $tweet->likes()->firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        return back();
    }

    public function destroy(Request $request, Tweet $tweet): RedirectResponse
    {
        $tweet->likes()
            ->where('user_id', $request->user()->id)
            ->delete();

        return back();
    }
}
