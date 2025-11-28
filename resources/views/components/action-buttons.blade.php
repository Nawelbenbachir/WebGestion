@props([
    'editUrl' => null,
     'deleteRoute' => null,   {{--  optionnel --}}
    'deleteLabel' => 'cet élément'  {{-- valeur par défaut --}}
])

<div class="flex items-center justify-center gap-3">
<!-- Bouton éditer -->
    @if($editUrl)
        <button type="button" data-edit-url="{{ $editUrl }}" class="text-xl hover:scale-110 transition">
    ✏️
</button>
    @endif
    <!-- Formulaire suppression -->
    <form method="POST"
          action="{{ $deleteRoute }}"
         onsubmit="return confirm('Supprimer {{ addslashes($deleteLabel) }} ?')"
          class="m-0 p-0">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-xl hover:scale-110 transition">
            🗑️
        </button>
    </form>

</div>
