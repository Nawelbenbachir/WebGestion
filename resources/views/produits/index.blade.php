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
                <th class="px-6 py-3 border-b border-gray-300 text-center">Prix</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produits as $produit)
                <tr data-id="{{ $produit->id }}" data-route="produits" class="cursor-pointer hover:bg-blue-100">
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $produit->code_produit }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $produit->description }}</td>
                    <td class="px-6 py-4 border-b border-gray-300">{{ $produit->prix_ht }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection