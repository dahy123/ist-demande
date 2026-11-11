{{-- Bouton de suppression avec confirmation. Sans libellé : simple icône pour les tableaux. --}}
@props(['action', 'confirm' => 'Confirmer la suppression ?', 'label' => null])

<form method="POST" action="{{ $action }}" onsubmit="return confirm(@js($confirm))" class="inline">
    @csrf
    @method('DELETE')
    @if ($label)
        <button type="submit" class="btn-danger"><x-icon name="trash" class="size-4" /> {{ $label }}</button>
    @else
        <button type="submit" class="row-action-danger" aria-label="Supprimer" title="Supprimer"><x-icon name="trash" class="size-4" /></button>
    @endif
</form>
