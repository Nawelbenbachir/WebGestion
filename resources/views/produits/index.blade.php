@extends('layouts.app')

@section('content')

<div class="overflow-x-auto rounded-lg shadow-md">
    <table class="w-full text-sm text-left text-gray-700 bg-white border border-gray-300">
        <thead class="text-xs uppercase bg-gray-100 text-gray-600">
            <tr>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Code</th>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Description</th>
                <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Prix</th>
                <th class="px-6 py-3 border-b border-gray-300 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produits as $produit)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $produit->code_produit }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ $produit->nom }}</td>
                    <td class="px-6 py-4 border-b border-r border-gray-300">{{ number_format($produit->prix, 2, ',', ' ') }} €</td>
                    <td class="px-6 py-4 border-b border-gray-300 flex gap-2">
                        <a href="{{ route('produits.destroy', $produit->id) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                            Modifier
                        </a>
                        <form action="{{ route('produits.edit', $produit->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection