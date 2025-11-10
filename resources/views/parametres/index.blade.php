@extends('layouts.table') 
@php 
    $createRoute = 'parametres.create';
    $createLabel = 'Ajouter une société'; 
@endphp 
@section('table') 
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
@endsection