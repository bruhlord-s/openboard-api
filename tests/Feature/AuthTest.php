<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegister()
    {
        \Artisan::call('passport:install');

        $response = $this->postJson('/api/register', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'name' => 'Test',
            'email' => 'test@test.com',
        ]);
    }

    public function testUserCantRegisterOnTakenEmail()
    {
        \Artisan::call('passport:install');

        User::create([
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password'
        ]);

        $response = $this->postJson('/api/register', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(422);
    }

    public function testUserCanLogin()
    {
        \Artisan::call('passport:install');

        // register a user
        $this->postJson('/api/register', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $response->assertOk();        
        $response->assertJsonStructure(['token']);
    }

    public function testUserCantLoginWithWrongCreds()
    {
        \Artisan::call('passport:install');

        // register a user
        $this->postJson('/api/register', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'another_password',
        ]);

        $response->assertStatus(422);
    }
}
