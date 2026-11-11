<?php

namespace App\Http\Controllers;

use App\Models\JournalAudit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalAuditController extends Controller
{
    public function __invoke(Request $request): View
    {
        $entrees = JournalAudit::with('utilisateur')
            ->when($request->query('q'), fn ($query, $terme) => $query->where(function ($query) use ($terme): void {
                $query->where('action', 'like', "%{$terme}%")->orWhere('detail', 'like', "%{$terme}%");
            }))
            ->when($request->query('utilisateur'), fn ($query, $utilisateurId) => $query->where('utilisateur_id', $utilisateurId))
            ->latest('date_heure')->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('journal.index', [
            'entrees' => $entrees,
            'utilisateurs' => User::orderBy('nom')->get(),
        ]);
    }
}
