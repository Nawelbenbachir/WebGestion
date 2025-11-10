@extends('layouts.table')

@php
    $createRoute = 'documents.create';
    $createLabel = 'Ajouter un devis';
@endphp

@section('table')

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- Vérifie s’il y a des documents --}}
    @if($documents->isEmpty())
        <div class="alert alert-info">
            Aucun document enregistré pour le moment.
        </div>
    @else
        <table id="documents-table" class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Code Devis</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Type</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Date</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Client</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Total TTC (€)</th>
                </tr>
            </thead>
            <tbody>
            @foreach($documents as $document)
                <tr data-id="{{ $document->id }}" class="cursor-pointer hover:bg-blue-100">
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $document->code_document }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ ucfirst($document->type_document) }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $document->date_document }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $document->client_nom }}</td>
                    <td class="text-end border-b border-r">{{ number_format($document->total_ttc, 2, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('#documents-table tbody tr');
    rows.forEach(row => {
        row.addEventListener('dblclick', function() {
            const documentId = this.dataset.id;
            const editUrl = `/documents/${documentId}/edit`; // route edit standard
            window.location.href = editUrl;
        });
    });
});
</script>
    @endif

@endsection
