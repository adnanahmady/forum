<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class SearchThreadController extends Controller
{
    public function index()
    {
        if (request()->expectsJson()) {
            $threads = Thread::search(request('s'))->paginate();

            return response()->json($threads, 200);
        }

        $userVisitedThreads = [];
        $visitedThreads = resolve('TrendingThreads')->get(5);

        if (auth()->check()) {
            $userVisitedThreads = resolve('UserVisit')->get(5);
        }

        return view('threads.search', compact(
            'userVisitedThreads',
            'visitedThreads'
        ));
    }
}
