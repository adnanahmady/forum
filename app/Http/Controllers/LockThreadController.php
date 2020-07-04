<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function store(Thread $thread)
    {
        $thread->setLock();

        return response()->json($thread, 200);
    }

    public function destroy(Thread $thread)
    {
        $thread->unlock();

        return response()->json([], 204);
    }
}
