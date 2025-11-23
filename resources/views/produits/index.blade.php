<x-layouts.app>
    <x-layouts.table createRoute="produits.create" createLabel="Ajouter un produit">

        <table id="produits-table" class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-0 py-4 border-b text-center border-gray-300 "></th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Code produit</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Description</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Catégorie</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Prix HT</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">TVA</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Stock</th>
                </tr>
            </thead>

            <tbody class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
                @foreach ($produits as $produit)
                    <tr data-id="{{ $produit->id }}" data-route="produits"
                        class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 even:bg-gray-50 dark:even:bg-gray-900">

                        {{-- Actions --}}
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">
                            <x-action-buttons 
                                :id="$produit->id"
                                :edit-url="route('produits.edit', $produit->id)"
                                :delete-route="route('produits.destroy', $produit->id)"
                                delete-label="le produit « {{ $produit->code_produit }} »"
                            />
                        </td>

                        {{-- Colonnes --}}
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $produit->code_produit }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $produit->description }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $produit->categorie ?? '—' }}</td>

                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">
                            {{ number_format($produit->prix_ht, 2, ',', ' ') }} €
                        </td>

                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">
                            {{ $produit->tva }} %
                        </td>

                        <td class="px-6 py-4 border-b text-end border-gray-300 dark:border-gray-700">
                            {{ $produit->qt_stock ?? 0 }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </x-layouts.table>
</x-layouts.app>
