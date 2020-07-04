<?php

namespace App\Http\Controllers;

use App\Subscribe;
use App\Thread;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $channel, Thread $thread)
    {
        $thread->subscribe();

        if ($request->expectsJson()) return response()->json([], 201);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscribe  $subscribe
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $thread->unsubscribe();

        if (request()->expectsJson()) return response([], 204);

        return back();
    }
}
