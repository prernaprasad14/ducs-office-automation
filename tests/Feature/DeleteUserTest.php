<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function office_can_delete_user()
    {
        $anotherUser = create(User::class);

        $this->signIn();

        $this->withoutExceptionHandling()
            ->from('/users')
            ->delete('/users/' . $anotherUser->id)
            ->assertRedirect('/users')
            ->assertSessionHasFlash('success', 'User deleted successfully!');

        $this->assertNull($anotherUser->fresh());
    }
}
