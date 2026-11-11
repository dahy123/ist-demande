<?php

namespace Tests\Feature;

use App\Models\Ecole;
use App\Models\JournalAudit;
use App\Models\Parcours;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_open_administration(): void
    {
        $this->actingAs(User::factory()->create())->get(route('ecoles.index'))->assertForbidden();
    }

    public function test_school_with_parcours_cannot_be_deleted(): void
    {
        $ecole = Ecole::factory()->create();
        Parcours::create(['code' => 'RT', 'ecole_id' => $ecole->id]);

        $this->actingAs(User::factory()->admin()->create())->delete(route('ecoles.destroy', $ecole))
            ->assertSessionHas('error');

        $this->assertModelExists($ecole);
    }

    public function test_admin_creates_user_with_roles(): void
    {
        $role = Role::factory()->create();

        $this->actingAs(User::factory()->admin()->create())->post(route('utilisateurs.store'), [
            'nom' => 'Rasoa',
            'identifiant' => 'rasoa',
            'password' => 'motdepasse',
            'password_confirmation' => 'motdepasse',
            'roles' => [$role->id],
            'actif' => '1',
        ])->assertRedirect(route('utilisateurs.index'));

        $utilisateur = User::firstWhere('identifiant', 'rasoa');
        $this->assertTrue($utilisateur->actif);
        $this->assertTrue($utilisateur->hasRole($role->code));
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->delete(route('utilisateurs.destroy', $admin))->assertSessionHas('error');

        $this->assertModelExists($admin);
    }

    public function test_changes_are_recorded_in_the_audit_journal(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('ecoles.store'), ['code' => 'EGI', 'nom' => 'Génie Industriel']);

        $this->assertDatabaseHas('journal_audit', ['utilisateur_id' => $admin->id, 'action' => 'Création', 'detail' => 'École EGI']);
        $this->actingAs($admin)->get(route('journal.index'))->assertOk()->assertSee('École EGI');
        $this->assertSame(1, JournalAudit::where('detail', 'École EGI')->count());
    }
}
