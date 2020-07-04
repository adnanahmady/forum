<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class profilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_can_delete_their_own_threads()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        auth()->user()->threads()->delete($thread->id);

        $this->assertNotInstanceOf('App\Thread', auth()->user()->threads()->first());
    }
}
