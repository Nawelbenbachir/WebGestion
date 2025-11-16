<x-layouts.app>
    <x-layouts.table createRoute="produits.create" createLabel="Ajouter un produit">

        <table id="produits-table" class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Code produit</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Description</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Catégorie</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Prix HT</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">TVA</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Stock</th>
                </tr>
            </thead>

            {{-- Corps du tableau --}}
            <tbody class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
                @foreach ($produits as $produit)
                    <tr data-id="{{ $produit->id }}" data-route="produits"
                        class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 even:bg-gray-50 dark:even:bg-gray-900 transition">
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $produit->code_produit }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $produit->description }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $produit->categorie ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-end border-gray-300 dark:border-gray-700">
                            {{ number_format($produit->prix_ht, 2, ',', ' ') }} €
                        </td>
                        <td class="px-6 py-4 border-b text-end border-gray-300 dark:border-gray-700">{{ $produit->tva }} %</td>
                        <td class="px-6 py-4 border-b text-end border-gray-300 dark:border-gray-700">{{ $produit->qt_stock ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modal d’édition produit --}}
        <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative flex flex-col max-h-[80vh]">

                <!-- Bouton fermer -->
                <button onclick="closeModal()" 
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 font-bold text-xl z-10">
                    ✖
                </button>

                <!-- Contenu scrollable -->
                <div id="produit-details" class="overflow-auto p-6 flex-1 space-y-4">
                    <!-- Formulaire injecté dynamiquement ici -->
                </div>

                <!-- Boutons en bas
                <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <x-secondary-button as="a" href="#" onclick="closeModal()">⬅️ Annuler</x-secondary-button>
                    <x-primary-button type="submit" form="produit-form">💾 Enregistrer</x-primary-button>
                </div> -->
            </div>
        </div>

        <script>
        function openModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Chargement dynamique du formulaire au clic sur une ligne
        document.addEventListener('DOMContentLoaded', () => {
            const deleteForm = document.getElementById('delete-form');
            if (!deleteForm) return;
            document.querySelectorAll('tr[data-id]').forEach(row => {
                row.addEventListener('dblclick', () => {
                    
                    const produitId = row.dataset.id;
                    fetch(`/produits/${produitId}/edit`)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('produit-details').innerHTML = html;
                            openModal();
                        })
                        .catch(err => console.error('Erreur de chargement :', err));
                });
                row.addEventListener('click', () => {
                    const id = row.dataset.id;
                    const route = row.dataset.route;

                    // Met à jour l’URL de suppression
                    deleteForm.setAttribute('action', `/${route}/${id}`);

                    // Affiche le bouton supprimer
                    deleteForm.classList.remove('hidden');
                });
            });
        });
        </script>

    </x-layouts.table>
</x-layouts.app>
