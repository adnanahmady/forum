<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sond_upon_registration()
    {
        Mail::fake();

        event(new Registered(factory('App\User')->create()));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_addresses()
    {
        $this->withoutExceptionHandling();
        $this->post('/register', [
            'name' => 'adnan',
            'email' => 'adnan@example.dev',
            'password' => 'adnanahmady',
            'password_confirmation' => 'adnanahmady',
        ]);

        $user = User::whereName('adnan')->first();

        $this->assertNull($user->confirm);
        $this->assertNotNull($user->confirm_token);
        $this->get(route('register.confirm', ['token' => $user->confirm_token]))
            ->assertRedirect('/threads')
            ->assertSessionHas('flash');
        tap($user->fresh(), function ($user) {
            $this->assertNotNull($user->confirm);
            $this->assertNull($user->confirm_token);
        });
    }

    /** @test */
    public function validating_invalid_confirm_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect('/threads')
            ->assertSessionHas(['flash', 'level']);
    }
}
