

   @props([
'createRoute' => null,
'createLabel' => 'Ajouter',
'hideModal' => false,
])

<div class="w-full p-6">

{{-- En-tête : titre + boutons --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
    @if($createRoute)
        <div class="flex gap-3">
            <x-primary-button
                type="button"
                onclick="openModal('{{ route($createRoute) }}{{ request('type') ? '?type=' . request('type') : '' }}')"
                class="bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all">
                {{ $createLabel }}
            </x-primary-button>
        </div>
    @endif
</div>

{{-- Tableau --}}
<div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto w-full border border-gray-100 dark:border-gray-700">
    {{ $slot }}
</div>

@if (!$hideModal)
{{-- Modal unique pour create/edit --}}
<div id="modal" class="fixed inset-0 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-5xl relative flex flex-col max-h-[90vh] border border-gray-200 dark:border-gray-700">

        <!-- Bouton fermer -->
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <!-- Contenu dynamique -->
        <div id="modal-content" class="overflow-y-auto p-6 flex-1"></div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    
    const rows = document.querySelectorAll('tr[data-id]');

    rows.forEach(row => {
        // 1. Gestion du clic simple pour la SÉLECTION visuelle
        row.addEventListener('click', (e) => {
            // MODIFICATION ICI : On vérifie si on clique sur un bouton, un lien, un formulaire OU un élément à l'intérieur (svg, path, etc.)
            if (e.target.closest('button, a, form, input, select')) {
                return; 
            }

            rows.forEach(r => {
                r.classList.remove('bg-blue-50', 'dark:bg-blue-900/20', 'selected-row');
            });
            row.classList.add('bg-blue-50', 'dark:bg-blue-900/20', 'selected-row');
        });

        // 2. Gestion du double-clic pour l'ÉDITION
        row.addEventListener('dblclick', (e) => {
            if (e.target.closest('button, a, form, input, select')) return;

            const route = row.dataset.route;
            const id = row.dataset.id;
            if (!route || !id) return;
            const url = `/${route}/${id}/edit`; 
            openModal(url);
        });
    });

    // 3. Clic sur les boutons d'édition (crayon)
    document.querySelectorAll('[data-edit-url]').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation(); 
            const url = btn.dataset.editUrl;
            openModal(url);
        });
    });
    
    
});

function openModal(url) {
    const content = document.getElementById('modal-content');
    // Affichage du loader
    content.innerHTML = '<div class="flex justify-center p-12"><svg class="animate-spin h-8 w-8 text-blue-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
    
    document.getElementById('modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('Erreur HTTP ' + res.status);
            return res.text();
        })
        .then(html => {
            const container = document.getElementById('modal-content');
            container.innerHTML = html;
            
            // Stylisation automatique des inputs injectés
            container.querySelectorAll('input:not([type="checkbox"]), select, textarea').forEach(el => {
                el.classList.add(
                    'w-full','rounded-md','border-gray-300','dark:border-gray-600',
                    'bg-white','dark:bg-gray-800','text-gray-900','dark:text-gray-100',
                    'shadow-sm','focus:border-blue-500','focus:ring-blue-500'
                );
            });

            // --- GESTION DU CHANGEMENT DE CLIENT ---
            const clientSelect = container.querySelector('#client_id');
            if (clientSelect) {
                clientSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.value !== "") {
                        const setVal = (id, val) => {
                            const el = container.querySelector(`#${id}`);
                            if(el) el.value = val || '';
                        };

                        setVal('adresse1', selectedOption.dataset.adresse1);
                        setVal('adresse2', selectedOption.dataset.adresse2);
                        setVal('code_postal', selectedOption.dataset.cp);
                        setVal('ville', selectedOption.dataset.ville);
                        setVal('telephone', selectedOption.dataset.tel);
                        setVal('email', selectedOption.dataset.email);
                    }
                });
            }

            // Initialisation spécifique aux formulaires de devis/factures
            if (container.querySelector('#lignesTable')) {
                initDevisForm(container);
            }
        }) 
        .catch(err => {
            console.error('Erreur :', err);
            closeModal();
        });
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function initDevisForm(container) {
    const tbody = container.querySelector('#lignesTable tbody');
    const datalist = container.querySelector('#produits');
    if (!tbody || !datalist) return;

    // --- 1. FONCTION DE CALCUL DES TOTAUX ---
    const updateTotals = () => {
        let totalHT = 0, totalTVA = 0, totalTTC = 0;
        
        tbody.querySelectorAll('tr').forEach(row => {
            const qte = parseFloat(row.querySelector('.quantite')?.value) || 0;
            const prix = parseFloat(row.querySelector('.prix')?.value) || 0;
            const tva = parseFloat(row.querySelector('.tva')?.value) || 0;
            
            const lineHT = qte * prix;
            const lineTVA = lineHT * (tva / 100);
            const lineTTC = lineHT + lineTVA;

            if(row.querySelector('.total')) row.querySelector('.total').value = lineTTC.toFixed(2);
            
            totalHT += lineHT;
            totalTVA += lineTVA;
            totalTTC += lineTTC;
        });

        // Mise à jour des affichages (span)
        if(container.querySelector('#display_total_ht')) container.querySelector('#display_total_ht').textContent = totalHT.toFixed(2) + ' €';
        if(container.querySelector('#display_total_tva')) container.querySelector('#display_total_tva').textContent = totalTVA.toFixed(2) + ' €';
        if(container.querySelector('#display_total_ttc')) container.querySelector('#display_total_ttc').textContent = totalTTC.toFixed(2) + ' €';
        
        // Mise à jour des inputs hidden (pour l'envoi du formulaire)
        if(container.querySelector('#total_ht')) container.querySelector('#total_ht').value = totalHT.toFixed(2);
        if(container.querySelector('#total_tva')) container.querySelector('#total_tva').value = totalTVA.toFixed(2);
        if(container.querySelector('#total_ttc')) container.querySelector('#total_ttc').value = totalTTC.toFixed(2);
    };

    // --- 2. GESTION DU CHOIX DE PRODUIT ---
    tbody.addEventListener('input', e => {
        if (e.target.classList.contains('produit-input')) {
            const val = e.target.value;
            const row = e.target.closest('tr');
            const option = Array.from(datalist.options).find(opt => opt.value === val);

            if (option) {
                // On remplit les champs de la ligne avec les data-attributes de l'option
                row.querySelector('.description').value = option.dataset.designation || '';
                row.querySelector('.prix').value = option.dataset.prix || 0;
                row.querySelector('.tva').value = option.dataset.tva || 0;
                updateTotals();
            }
        }
        
        // Si on change la quantité manuellement
        if (e.target.classList.contains('quantite')) {
            updateTotals();
        }
    });

    // --- 3. AJOUT ET SUPPRESSION DE LIGNES ---
    tbody.addEventListener('click', e => {
        // Bouton Supprimer (on cherche le bouton ou le SVG à l'intérieur)
        if (e.target.closest('.removeRow')) {
            const rows = tbody.querySelectorAll('tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                updateTotals();
            } else {
                alert("Vous devez garder au moins une ligne.");
            }
        }

        // Bouton Ajouter en dessous
        if (e.target.closest('.addRowBelow')) {
            const currentRow = e.target.closest('tr');
            const newRow = currentRow.cloneNode(true);
            
            // Réinitialiser les valeurs de la nouvelle ligne
            const index = tbody.querySelectorAll('tr').length;
            newRow.querySelectorAll('input').forEach(input => {
                // On vide la valeur
                input.value = input.classList.contains('quantite') ? 1 : '';
                // On met à jour l'index du nom (ex: lignes[0] -> lignes[1])
                if(input.name) {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                }
            });

            currentRow.after(newRow);
        }
    });
}

        
</script>
@endpush
@endif


</div>