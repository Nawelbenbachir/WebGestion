@extends('layouts.table') 
@php 
    $createRoute = 'clients.create';
    $createLabel = 'Ajouter un client'; 
@endphp 
@section('table') 
<table id="clients-table" class="min-w-full w-full border border-gray-200"> 
    <thead class="bg-gray-100"> 
        <tr> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Société</th> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Type</th> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Téléphone</th>
            <th class="px-6 py-3 border-b  border-r border-gray-300 text-center">Email</th> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Adresse</th>
             <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Code Postal</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Ville</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Mode de règlement</th>
        </tr> 
    </thead> 
    <tbody> @foreach ($clients as $client) 
        <tr data-id="{{ $client->id }}" data-route="clients" class="cursor-pointer w-full hover:bg-blue-100"> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->societe }}</td> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ ucfirst($client->type) }}</td> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->telephone ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->email ?? '—' }}</td> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->adresse1 ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->code_postal ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->ville ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->reglement ?? '—' }}</td>
        </tr>
        @endforeach 
    </tbody> 
</table>
 @endsection