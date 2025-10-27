@extends('layouts.table')

@php
    $title = 'Liste des clients';
    $createRoute = 'clients.create';
    $createLabel = 'Ajouter un client';
@endphp

@section('table')
    <table id="clients-table" class="min-w-full border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Nom</th>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Type</th>
                <th class="px-6 py-3 border-b border-gray-300 text-center">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr data-id="{{ $client->id }}" class="cursor-pointer hover:bg-blue-100">
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $client->nom }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ ucfirst($client->type) }}</td>
                    <td class="px-6 py-4 border-b border-gray-300">{{ $client->email ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <script>
        document.querySelectorAll('#clients-table tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                const id = row.getAttribute('data-id');
                document.querySelectorAll('#clients-table tr').forEach(r => r.classList.remove('bg-blue-200'));
                row.classList.add('bg-blue-200');

                // Met à jour les liens dynamiquement
                document.getElementById('view-btn').href = `/clients/${id}`;
                document.getElementById('edit-btn').href = `/clients/${id}/edit`;
                document.getElementById('delete-form').action = `/clients/${id}`;

                // Affiche la zone d’action
                document.getElementById('action-buttons').classList.remove('hidden');
            });
        });
    </script>
@endsection
