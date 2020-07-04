<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class VisitThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function visited_threads_are_counted()
    {
        $threadOne = factory('App\Thread')->create();
        $threadTwo = factory('App\Thread')->create();

        $threadOne->visits()->reset();
        $threadTwo->visits()->reset();

        $this->assertSame(0, $threadOne->visits()->count());

        $this->get($threadOne->path());
        $this->get($threadOne->path());

        $this->assertSame(2, $threadOne->visits()->count());
        $this->assertSame(0, $threadTwo->visits()->count());

        $threadOne->visits()->reset();
        $threadTwo->visits()->reset();
    }
}
