@extends('layouts.table')

@php
    $createRoute = 'produits.create';
    $createLabel = 'Ajouter un produit';
@endphp

@section('table')
    <table id="produits-table" class="min-w-full w-full border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Code</th>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Description</th>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Prix</th>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center"> Stock </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produits as $produit)
                <tr data-id="{{ $produit->id }}" data-route="produits" class="cursor-pointer hover:bg-blue-100">
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $produit->code_produit }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $produit->description }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $produit->prix_ht }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $produit->qt_stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
{{-- Zone du formulaire d’édition, vide au départ --}}
<div id="produit-details" class="bg-gray-50 shadow-inner rounded-lg p-4 mt-4 hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('tr[data-id]').forEach(row => {
        row.addEventListener('click', () => {
            const produitId = row.dataset.id;
            fetch(`/produits/${produitId}/edit`)
                .then(res => res.text())
                .then(html => {
                    const detailsDiv = document.getElementById('produit-details');
                    detailsDiv.innerHTML = html; // injecte le formulaire
                    detailsDiv.classList.remove('hidden');
                    detailsDiv.scrollIntoView({ behavior: 'smooth' });
                });
        });
    });
});
</script>
@endsection