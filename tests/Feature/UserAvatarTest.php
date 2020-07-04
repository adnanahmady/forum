<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_authenticated_users_are_able_to_upload_their_avatar()
    {
        $this->json('post', route('avatar', '1'))
            ->assertStatus(401);
    }

    /** @test */
    public function user_must_upload_valid_avatars()
    {
        $this->be(factory(User::class)->create());

        $this->json('post', route('avatar', auth()->user()), [
            'avatar' => 'non-avatar-file'
        ])->assertStatus(422);
    }

    /** @test */
    public function user_can_upload_its_avatar()
    {
        $this->withoutExceptionHandling();
        $this->be(factory(User::class)->create());

        Storage::fake('public');

        $this->json('post', route('avatar', auth()->user()), [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
        $this->assertDatabaseHas(
            'users',
            [
                'id' => auth()->id(),
                'avatar_path' => 'avatars/' . $file->hashName()
            ]
        );
    }
}
