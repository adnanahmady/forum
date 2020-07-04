<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestReplyController extends Controller
{
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);
        $reply->thread->bestReply($reply);

        if (request()->acceptsJson())
        {
            return response()->json($reply, 200);
        }

        return back()->with('flash', "Your best reply have accepted");
    }
}
