<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/login')->assertOk()->assertSee('Connexion');
    }

    public function test_user_can_login_with_identifiant(): void
    {
        $user = User::factory()->create(['identifiant' => 'agent1']);

        $this->post('/login', ['identifiant' => 'agent1', 'password' => 'password'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
        $this->get('/dashboard')->assertOk()->assertSee('Tableau de bord');
        $this->get('/parametres/profil')->assertOk()->assertSee('agent1');
    }

    public function test_wrong_password_or_inactive_user_is_rejected(): void
    {
        User::factory()->create(['identifiant' => 'agent1']);
        User::factory()->inactif()->create(['identifiant' => 'agent2']);

        $this->post('/login', ['identifiant' => 'agent1', 'password' => 'mauvais'])->assertSessionHasErrors('identifiant');
        $this->post('/login', ['identifiant' => 'agent2', 'password' => 'password'])->assertSessionHasErrors('identifiant');
        $this->assertGuest();
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/parametres/profil', ['nom' => 'Rakoto', 'prenoms' => 'Jean'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Rakoto', $user->fresh()->nom);

        $this->actingAs($user)->put('/parametres/mot-de-passe', [
            'current_password' => 'password',
            'password' => 'nouveau-mdp',
            'password_confirmation' => 'nouveau-mdp',
        ])->assertSessionHasNoErrors();

        $this->post('/logout');
        $this->post('/login', ['identifiant' => $user->identifiant, 'password' => 'nouveau-mdp'])->assertRedirect('/dashboard');
    }
}
