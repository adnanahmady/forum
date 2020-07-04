<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivitiesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function when_ever_a_thread_is_created_an_activity_is_created_for()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();

        $this->assertDatabaseHas('activities', [
            'user_id' => auth()->id(),
            'type' => 'created_thread',

            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $this->assertEquals(Activity::first()->id, $thread->id);
    }

    /** @test */
    public function with_deleting_a_thread_its_activities_will_be_deleted()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        factory('App\Reply')->create(['thread_id' => $thread->id]);
        Thread::find($thread->id)->delete();

        $this->assertEquals(0, Activity::count());
    }
}
