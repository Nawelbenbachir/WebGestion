@extends('layouts.table')

@php
    $createRoute = 'clients.create';
    $createLabel = 'Ajouter un client';
@endphp

@section('table')
    <table id="clients-table" class="min-w-full border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Société</th>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Type</th>
                <th class="px-6 py-3 border-b border-gray-300 text-center">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr data-id="{{ $client->id }}" data-route="clients" class="cursor-pointer hover:bg-blue-100">
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->societe }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ ucfirst($client->type) }}</td>
                    <td class="px-6 py-4 border-b border-gray-300">{{ $client->email ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    
@endsection
