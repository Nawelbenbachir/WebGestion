@extends('layouts.table')

@php
    $title = 'Liste des produits';
    $createRoute = 'produits.create';
    $createLabel = 'Ajouter un produit';
@endphp

@section('table')

    @if($produits->count() > 0)
        <table class="min-w-full border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Code Produit</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Nom</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Prix HT</th>
                    <th class="px-6 py-3 border-b border-r border-gray-300 text-center">TVA</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produits as $produit)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 border-b border-r border-gray-300 text-center">{{ $produit->code_produit }}</td>
                        <td class="px-6 py-4 border-b border-r border-gray-300 text-center">{{ $produit->description }}</td>
                        <td class="px-6 py-4 border-b border-r border-gray-300 text-center">
                            {{ number_format($produit->prix_ht, 2, ',', ' ') }} €
                        </td>
                        <td class="px-6 py-4 border-b border-r border-gray-300 text-center">{{ $produit->tva }}%</td>
                        <td class="px-6 py-4 border-b border-gray-300 text-center">
                            <a href="{{ route('produits.edit', $produit->id) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-lg transition">
                                ✏️ Modifier
                            </a>
                            <form action="{{ route('produits.destroy', $produit->id) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('Confirmer la suppression de ce produit ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-lg transition">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    @else
        <p class="text-gray-500 text-center">Aucun produit enregistré.</p>
    @endif

@endsection
