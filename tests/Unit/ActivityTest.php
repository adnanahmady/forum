<?php

namespace Tests\Unit;

use App\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_activity_fetches_a_feed_for_authorized_user()
    {
        $this->be(factory('App\User')->create());
        factory('App\Thread', 2)->create(['user_id' => auth()->id()]);

        auth()
            ->user()
            ->activities()
            ->first()
            ->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y/m/d D')
        ));
    }
}
