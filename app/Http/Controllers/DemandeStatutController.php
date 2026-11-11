<?php

namespace App\Http\Controllers;

use App\Enums\StatutDemande;
use App\Models\Demande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Fait avancer une demande dans le circuit de traitement.
 */
class DemandeStatutController extends Controller
{
    public function __invoke(Request $request, Demande $demande): RedirectResponse
    {
        $data = $request->validateWithBag('statut', [
            'statut' => ['required', Rule::enum(StatutDemande::class)],
            'agent_id' => [Rule::requiredIf($request->input('statut') === StatutDemande::Affectee->value), 'nullable', 'exists:users,id'],
            'commentaire' => [Rule::requiredIf($request->input('statut') === StatutDemande::Refusee->value), 'nullable', 'string', 'max:200'],
        ], [
            'agent_id.required' => "Choisissez l'agent à qui affecter la demande.",
            'commentaire.required' => 'Indiquez le motif du refus.',
        ]);

        $nouveauStatut = StatutDemande::from($data['statut']);

        if ($erreur = $this->blocage($demande, $nouveauStatut)) {
            return back()->with('error', $erreur);
        }

        DB::transaction(function () use ($demande, $data, $nouveauStatut): void {
            if (filled($data['agent_id'] ?? null)) {
                $demande->agent_id = $data['agent_id'];
            }

            $demande->changerStatut($nouveauStatut, $data['commentaire'] ?? null);
            $demande->save();
        });

        return back()->with('status', "Statut passé à « {$nouveauStatut->label()} ».");
    }

    /** Raison pour laquelle la demande ne peut pas encore passer à ce statut. */
    private function blocage(Demande $demande, StatutDemande $nouveauStatut): ?string
    {
        if ($nouveauStatut === StatutDemande::PaiementVerifie && ! $demande->estPayee()) {
            return 'Le paiement enregistré ne couvre pas le montant attendu.';
        }

        if ($nouveauStatut === StatutDemande::Recevable && $demande->piecesObligatoiresManquantes()->isNotEmpty()) {
            return 'Toutes les pièces obligatoires doivent être fournies avant de déclarer la demande recevable.';
        }

        return null;
    }
}
