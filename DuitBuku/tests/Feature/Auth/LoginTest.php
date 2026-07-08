<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_valid_credentials_redirect_to_dashboard(): void
    {
        $this->post('/login', [
            'email'    => 'owner@duitbuku.test',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));
    }

    public function test_wrong_password_redirects_back_with_error(): void
    {
        $this->from('/login')
             ->post('/login', [
                 'email'    => 'owner@duitbuku.test',
                 'password' => 'wrongpassword',
             ])
             ->assertRedirect('/login')
             ->assertSessionHasErrors('email');
    }

    public function test_missing_email_fails_validation(): void
    {
        $this->from('/login')
             ->post('/login', ['password' => 'password'])
             ->assertSessionHasErrors('email');
    }

    public function test_logout_redirects_to_login(): void
    {
        $user = User::where('email', 'owner@duitbuku.test')->first();

        $this->actingAs($user)
             ->post('/logout')
             ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_is_logged_out_after_logout(): void
    {
        $user = User::where('email', 'owner@duitbuku.test')->first();

        $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }
}
