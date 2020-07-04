<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReplyRequest;
use App\Reply;
use App\Thread;
use Illuminate\Http\JsonResponse;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    public function index($channel = null, Thread $thread)
    {
        if (request()->expectsJson()) {
            return response()->json($thread->replies()->paginate(15), 200);
        }
    }

    /**
     * Store a reply for a thread
     *
     * @param CreateReplyRequest $request
     * @param $channelSlug
     * @param Thread $thread
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateReplyRequest $request, $channelSlug, Thread $thread)
    {
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }
        $reply = $thread->addReply(
            [
                'body' => $request->get('body'),
                'user_id' => auth()->id()
            ]
        );

        if ($request->expectsJson()) {
            return response()->json($reply->load('owner'), 201);
        }

        return redirect($thread->path())->with('flash', __('You left a reply'));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response([], 204);
        }

        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $this->validate(request(), ['body' => 'required|min:1|spamfree']);

        $reply->update(request(['body']));

        if (request()->wantsJson()) {
            return response()->json($reply, 200);
        }

        return back();
    }
}
