<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_home_page()
    {
        // Exception Handling
        // $response = $this->withoutExceptionHandling()->get('/');
        // you can check
        // your application does not use functions declared by deprecated language or PHP libraries
        $response = $this->withoutDeprecationHandling()->get('/');

        $response->assertStatus(200);
    }

    public function test_headers()
    {
        $response = $this->withHeaders([
                "ExampleHeader" => "Example"
            ])->json('POST', '/users', ['data' => "HelloWorld"]);

        $response->assertStatus(200)->assertJson([
                'status' => "success"
            ])->assertHeader("ResponseHeader", "Response");
    }

    public function test_session()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withSession([
            'user' => "Ben"
        ])->withCookie('color', 'red')->get('/test_session_cookie');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
        $this->assertDatabaseMissing('users',[
            'email' => 'sdfsdfncorwin@example.org'
        ]);
    }

    public function test_user_can_view_login_form()
    {
        $response = $this->get('login');
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_user_can_not_login_with_in_correct_credentials()
    {
        $password = 'password';

        $user = User::factory()->create([
            'password' => bcrypt($password)
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => "new password",
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_asserting_an_exact_json_match()
    {
        $response = $this->putJson('/users/1', ['name' => 'Sally']);

        $response
            ->assertStatus(200)
            ->assertJsonPath('user.name', fn($name) => strlen($name) >= 3)
            ->assertJson(fn(AssertableJson $json) => $json->where('user.id', 1)
                ->whereType('user.name', 'string|null')
                ->whereType('user.id', ['string', 'integer'])
                ->where('user.name', 'Sally')
                ->whereNot('user.email', 'testtest@example.com')
                ->missing('user.status')
                ->has('user.created_at')
                ->hasAny('user.data', 'user.message', 'user.name')
                ->etc());
    }

    public function test_asserting_json_collections()
    {
        $response = $this->getJson('/users');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('success')
                ->has('users', 5)
                ->has('users.0', fn ($json) =>
                $json->where('id', 1)
                    ->where('name', 'Sally')
                    ->missing('password')
                    ->etc())
            );
    }

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->withoutExceptionHandling()->get('/register');

        // methods to dump information about the response and then stop execution
        // $response->ddHeaders();
        // $response->ddSession();
        // $response->dd();

        $response->assertStatus(200);
    }

}
