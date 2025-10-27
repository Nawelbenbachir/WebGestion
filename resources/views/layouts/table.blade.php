@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    {{-- 🔹 En-tête : titre + boutons --}}
    <div class="flex flex-col items-start mb-6 gap-3">
        <h2 class="text-2xl font-semibold">{{ $title ?? 'Liste' }}</h2>

        @if(isset($createRoute))
            {{-- Bouton Ajouter --}}
            <a href="{{ route($createRoute) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full sm:w-auto text-center">
               ➕ {{ $createLabel ?? 'Ajouter' }}
            </a>

            {{-- Bouton Voir --}}
            <a id="view-btn" href="#" 
               class="hidden bg-indigo-500 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg w-full sm:w-auto text-center">
                👁️ Voir
            </a>

            {{-- Bouton Modifier --}}
            <a id="edit-btn" href="#" 
               class="hidden bg-yellow-500 hover:bg-yellow-700 text-white py-2 px-4 rounded-lg w-full sm:w-auto text-center">
                ✏️ Modifier
            </a>

            {{-- Bouton Supprimer --}}
            <form id="delete-form" method="POST" action="#" 
                  class="hidden w-full sm:w-auto"
                  onsubmit="return confirm('Supprimer cet élément ?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded-lg w-full sm:w-auto text-center">
                    🗑️ Supprimer
                </button>
            </form>
        @endif
    </div>

    {{-- 🔹 Zone du tableau --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @yield('table')
    </div>

</div>

{{-- 🔹 Script pour activer les boutons selon la ligne sélectionnée --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('tr[data-id]').forEach(row => {
            row.addEventListener('click', () => {
                const id = row.dataset.id;
                const baseRoute = row.dataset.route;

                // Met à jour les liens
                document.getElementById('view-btn').href = `/${baseRoute}/${id}`;
                document.getElementById('edit-btn').href = `/${baseRoute}/${id}/edit`;
                document.getElementById('delete-form').action = `/${baseRoute}/${id}`;

                // Affiche les boutons
                document.getElementById('view-btn').classList.remove('hidden');
                document.getElementById('edit-btn').classList.remove('hidden');
                document.getElementById('delete-form').classList.remove('hidden');
            });
        });
    });
</script>
@endsection
