<?php

namespace App\Filters;


use App\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'replies', 'unanswered'];

    protected function by($username)
    {
        $user = User::whereName($username)->firstOrFail();

        $this->builder->where('user_id', $user->id);
    }

    protected function replies($order)
    {
        $this->builder->getQuery()->orders = [];

        $this->builder->orderBy('replies_count', 0 < (int) $order ? 'desc' : 'asc');
    }

    protected function unanswered($unanswered)
    {
        $this->builder->where('replies_count', 0);
    }
}
