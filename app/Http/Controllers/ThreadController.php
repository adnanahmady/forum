<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Rules\Recaptcha;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(
            [
                'index',
                'show'
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);
        $userVisitedThreads = [];
        $visitedThreads = resolve('TrendingThreads')->get(5);

        if (auth()->check()) {
            $userVisitedThreads = resolve('UserVisit')->get(5);
        }

        if (request()->wantsJson()) {
            return response()->json(
                compact(
                    'threads',
                    'userVisitedThreads',
                    'visitedThreads'
                ),
                200
            );
        }

        return view('threads.index', compact(
            'threads',
            'userVisitedThreads',
            'visitedThreads'
        ));
    }

    /**
     * applies filters and returns $threads
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     *
     * @return Collection $threads
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::with('channel')
            ->latest()
            ->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(25);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Recaptcha $recaptcha
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Recaptcha $recaptcha)
    {
        $this->validate($request, [
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => ['required', $recaptcha]
        ]);

        $thread = auth()->user()->threads()->create(
            [
                'channel_id' => $request->get('channel_id'),
                'title' => $request->get('title'),
                'body' => $request->get('body')
            ]
        );

        return redirect($thread->path())->with('flash', 'You Have left a thread');
    }

    /**
     * Display the specified resource.
     *
     * @param        $channelSlug
     * @param Thread $thread
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function show($channelSlug, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }
        app('TrendingThreads')->push([
            'id' => $thread->id,
            'title' => $thread->title,
            'path' => $thread->path()
        ]);
        $thread->visits()->record();

        if (request()->expectsJson()) {
            return response()->json($thread, 200);
        }

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * update thread with new title and body
     *
     * @param $channel
     * @param Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => 'required|min:5|spamfree',
            'body' => 'required|min:5|spamfree',
        ]));

        return response($thread, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $channel
     * @param \App\Thread $thread
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Request $request, $channel, Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        if ($request->wantsJson()) {
            return response()->json([], 204);
        }

        return redirect('/threads');
    }
}
