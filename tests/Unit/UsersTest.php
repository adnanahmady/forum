<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_access_its_latest_reply()
    {
        $this->be(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $reply = $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some body'
        ]);

        $this->assertEquals($reply->id, auth()->user()->latestReply->id);
    }

    /** @test */
    public function a_user_can_knows_its_avatar_path()
    {
        $user = factory('App\User')->create();

        $user->avatar_path = 'avatar/me.jpg';

        $this->assertEquals(asset('/storage/avatar/me.jpg'), $user->avatar_path);
    }

    /** @test */
    public function for_a_user_that_has_no_avatar_a_default_avatar_will_be_set()
    {
        $user = factory('App\User')->create();

        $this->assertEquals(asset('/images/avatars/default.png'), $user->avatar_path);
    }

    /** @test */
    public function user_has_confirmed_column()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->state('unconfirmed')->create();

        $this->assertNull($user->confirm);
    }
}
