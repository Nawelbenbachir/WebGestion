@extends('layouts.table')

@section('table')
@php 
    $createRoute = 'parametres.create';
    $createLabel = 'Ajouter une société'; 
    
@endphp 
<div id="edit-button-container-societe" class="mb-4 hidden">
    <a id="edit-button-societe" href="#" class=" bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-auto text-center">Modifier la société sélectionnée</a>
</div>

<h2 class="text-xl font-semibold mb-4">Liste des sociétés</h2>
<table id="parametres-table" class="min-w-full w-full border border-gray-200"> 
    <thead class="bg-gray-100"> 
        <tr> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center"> Code</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center"> Siret</th> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Nom</th> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Téléphone</th>
            <th class="px-6 py-3 border-b  border-r border-gray-300 text-center">Portable 1</th>
            <th class="px-6 py-3 border-b  border-r border-gray-300 text-center">Portable 2</th>
            <th class="px-6 py-3 border-b  border-r border-gray-300 text-center">Email</th> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Adresse</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Adresse 2</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Complément d'adresse</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Code Postal</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Ville</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">IBAN</th>
        </tr> 
    </thead> 
    <tbody> @foreach ($societes as $societe) 
        <tr data-id="{{ $societe->id }}" data-route="societes" class="cursor-pointer w-full hover:bg-blue-100"> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->code_societe }}</td> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->siret ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ ucfirst($societe->nom_societe) }}</td> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->telephone ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->portable1 ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->portable2 ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->email ?? '—' }}</td> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->adresse1 ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->adresse2 ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->complement_adresse ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->code_postal ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->ville ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $societe->iban ?? '—' }}</td>
        </tr>
        @endforeach 
    </tbody> 
</table>

 <h2 class="text-xl font-semibold mb-4">Liste des utilisateurs</h2>
 {{-- Bouton Ajouter --}}
    <a href="{{ route('user.create') }}" class=" bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-auto text-center">➕Ajouter un utilisateur</a>


{{-- Bouton Supprimer --}}
    <form id="delete-form" method="POST" action="#" 
        class="hidden w-full sm:w-auto"
         onsubmit="return confirm('Supprimer cet élément ?')">
        @csrf
         @method('DELETE')
        <button type="submit" 
             class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg w-auto text-center">
            🗑️ Supprimer
        </button>
    </form>

<div id="edit-button-container-user" class="mb-4 hidden">
    <a id="edit-button-user" href="#" class=" bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-auto text-center">Modifier l'utilisateur sélectionné</a>
</div>
<table id="users-table" class="min-w-full w-full border border-gray-200"> 
    <thead class="bg-gray-100"> 
        <tr> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center"> ID</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Email</th> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Nom</th> 
        </tr>
    </thead>
    <tbody> @foreach ($users as $user) 
        <tr data-id="{{ $user->id }}" data-route="users" class="cursor-pointer w-full hover:bg-blue-100"> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $user->id }}</td> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $user->email ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ ucfirst($user->name) }}</td> 
        </tr>
        @endforeach
    </tbody>
</table>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // -------- Table des utilisateurs --------
    const editButtonContainerUser = document.getElementById('edit-button-container-user');
    const editButtonUser = document.getElementById('edit-button-user');

    document.querySelectorAll('#users-table tbody tr').forEach(row => {
        row.addEventListener('click', () => {
            const userId = row.dataset.id;

            document.querySelectorAll('#users-table tbody tr').forEach(r => r.classList.remove('bg-blue-200'));
            row.classList.add('bg-blue-200');

            editButtonUser.href = `/user/${userId}/edit`;
            editButtonContainerUser.classList.remove('hidden');

            deleteForm.setAttribute('action', `/user/${userId}`);
            deleteForm.classList.remove('hidden');
        });
    });

    // -------- Table des sociétés --------
    const editButtonContainerSociete = document.getElementById('edit-button-container-societe');
    const editButtonSociete = document.getElementById('edit-button-societe');

    document.querySelectorAll('#parametres-table tbody tr').forEach(row => {
        row.addEventListener('click', () => {
            const societeId = row.dataset.id;

            // Surbrillance de la ligne sélectionnée
            document.querySelectorAll('#parametres-table tbody tr').forEach(r => r.classList.remove('bg-blue-200'));
            row.classList.add('bg-blue-200');

            // Affiche le bouton modifier
            editButtonSociete.href = `/parametres/${societeId}/edit`; // route resource
            editButtonContainerSociete.classList.remove('hidden');
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const deleteForm = document.getElementById('delete-form');

    document.querySelectorAll('tr[data-id]').forEach(row => {
        row.addEventListener('click', () => {
            const clientId = row.dataset.id;
            const baseRoute = row.dataset.route;

            // Met à jour l'action du formulaire suppression
            deleteForm.setAttribute('action', `/${baseRoute}/${clientId}`);

            // Affiche le formulaire si caché
            deleteForm.classList.remove('hidden');
        });
    });
});
</script>


@endsection