<?php

namespace Tests\Feature;

use App\Activity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_see_its_profile()
    {
        $this->be(factory('App\User')->create());

        $this->get(route('profile', auth()->user()))
            ->assertSee(auth()->user()->name);
    }

    /** @test */
    public function a_user_can_see_its_activities_on_thims_profile()
    {
        $this->be(factory('App\User')->create());
        $usersThread    = factory('App\Thread')->create(['user_id' => auth()->id()]);
        $notUsersThread = factory('App\Thread')->create();

        $this->withoutExceptionHandling()
            ->get(route('profile', auth()->user()))
            ->assertSee($usersThread->title)
            ->assertSee($notUsersThread->title)
        ;
    }

    /** @test */
    public function a_user_when_visiting_its_profile_can_see_its_assocciated_threads()
    {
        $this->be(factory('App\User')->create());

        $usersThread    = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this->withoutExceptionHandling()
            ->get(route('profile', auth()->user()))
            ->assertSee($usersThread->title)
            ->assertSee($usersThread->body)
        ;
    }
}
