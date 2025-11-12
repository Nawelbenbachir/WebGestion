<x-layouts.app>
    <x-layouts.table createRoute="produits.create" createLabel="Ajouter un produit">
        <!-- Formulaire d'ajout produit -->
        <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative flex flex-col max-h-[80vh]">

                <!-- Bouton fermer -->
                <button onclick="closeModal()" 
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 font-bold text-xl z-10">
                    ✖
                </button>

                <!-- Contenu scrollable -->
                <div class="overflow-auto p-6 flex-1 space-y-4">
                    <!-- Affichage des erreurs -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="produit-form" action="{{ route('produits.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="code_produit" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Code produit</label>
                                <input type="text" name="code_produit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                       value="{{ old('code_produit') ?: 'PRD' . strtoupper(substr(old('description') ?? '', 0, 3)) . rand(100, 999) }}">
                            </div>

                            <div>
                                <label for="code_comptable" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Code comptable</label>
                                <input type="text" name="code_comptable" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                       value="{{ old('code_comptable') ?: 'CPT' . strtoupper(substr(old('nom') ?? '', 0, 1)) . now()->format('YmdHis') }}">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                                <input type="text" name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                       value="{{ old('description') }}" required>
                            </div>

                            <div>
                                <label for="categorie" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Catégorie</label>
                                <select name="categorie" id="categorie" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">-- Sélectionner une catégorie --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" @selected(old('categorie') == $cat)>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="nouvelle_categorie" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nouvelle catégorie (optionnelle)</label>
                                <input type="text" name="nouvelle_categorie" id="nouvelle_categorie" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                       value="{{ old('nouvelle_categorie') }}">
                            </div>

                            <div>
                                <label for="prix_ht" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Prix HT</label>
                                <input type="text" name="prix_ht" id="prix_ht" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                       value="{{ old('prix_ht') }}" required>
                            </div>

                            <div>
                                <label for="tva" class="block text-sm font-medium text-gray-700 dark:text-gray-200">TVA</label>
                                <select name="tva" id="tva" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="20" @selected(old('tva') == '20')> 20</option>
                                    <option value="10" @selected(old('tva') == '10')> 10</option>
                                    <option value="5.5" @selected(old('tva') == '5.5')>5.5</option>
                                </select>
                            </div>

                            <div>
                                <label for="qt_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Stock</label>
                                <input type="text" name="qt_stock" id="qt_stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                       value="{{ old('qt_stock') }}">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Boutons en bas -->
                <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <x-secondary-button as="a" href="{{ route('produits.index') }}">⬅️ Annuler</x-secondary-button>
                    <x-primary-button type="submit" form="produit-form">💾 Enregistrer</x-primary-button>
                </div>
            </div>
        </div>

        <script>
            function closeModal() {
                document.getElementById('editModal').classList.add('hidden');
            }
            function openModal() {
                document.getElementById('editModal').classList.remove('hidden');
            }

            document.addEventListener('DOMContentLoaded', () => {
                openModal(); // ouvrir le modal directement pour "ajouter" un produit
            });
        </script>
    </x-layouts.table>
</x-layouts.app>
