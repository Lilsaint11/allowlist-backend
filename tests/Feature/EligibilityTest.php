<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EligibilityTest extends TestCase
{
    use RefreshDatabase; // resets the test DB schema before each test

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->getJson('/api/dashboard');

        $response->assertStatus(401);
    }

    public function test_user_can_register_and_receive_token()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token']);
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422); // ValidationException
    }

    public function test_user_can_create_a_wallet()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/wallets', [
            'address' => '0x' . str_repeat('a', 40),
            'label' => 'Main Vault',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['label' => 'Main Vault']);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $user->id,
            'label' => 'Main Vault',
        ]);
    }

    public function test_wallet_creation_rejects_invalid_address()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/wallets', [
            'address' => 'not-a-real-address',
            'label' => 'Bad Wallet',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_delete_another_users_wallet()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $wallet = Wallet::create([
            'user_id' => $userA->id,
            'address' => '0x' . str_repeat('b', 40),
            'label' => 'User A Wallet',
        ]);

        $response = $this->actingAs($userB, 'sanctum')->deleteJson("/api/wallets/{$wallet->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('wallets', ['id' => $wallet->id]); // still exists
    }

    public function test_cycling_eligibility_status_moves_through_states()
    {
        $user = User::factory()->create();
        $wallet = Wallet::create(['user_id' => $user->id, 'address' => '0x' . str_repeat('c', 40)]);
        $project = Project::create(['user_id' => $user->id, 'name' => 'Test Project']);

        // First cycle: unchecked -> eligible
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/eligibility/cycle', [
            'wallet_id' => $wallet->id,
            'project_id' => $project->id,
        ]);
        $response->assertJsonFragment(['status' => 'eligible']);

        // Second cycle: eligible -> not_eligible
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/eligibility/cycle', [
            'wallet_id' => $wallet->id,
            'project_id' => $project->id,
        ]);
        $response->assertJsonFragment(['status' => 'not_eligible']);
    }

    public function test_dashboard_only_returns_own_users_data()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Project::create(['user_id' => $userA->id, 'name' => 'A Project']);
        Project::create(['user_id' => $userB->id, 'name' => 'B Project']);

        $response = $this->actingAs($userA, 'sanctum')->getJson('/api/dashboard');

        $response->assertJsonCount(1, 'projects')
            ->assertJsonFragment(['name' => 'A Project'])
            ->assertJsonMissing(['name' => 'B Project']);
    }
}