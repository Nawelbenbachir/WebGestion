
@props([
    'createRoute' => null,
    'createLabel' => 'Ajouter',
    'hideModal' => false,
])

<div class="w-full p-6" x-data="{ 
    search: '',
    // Cette fonction vérifie si une ligne contient le texte recherché
    isMatch(el) {
        if (!this.search) return true;
        return el.innerText.toLowerCase().includes(this.search.toLowerCase());
    }
}">

   <div class="flex flex-col items-start mb-6 gap-4">
    
  
    @if($createRoute)
        <x-primary-button
            type="button"
            onclick="openModal('{{ route($createRoute) }}{{ request('type') ? '?type=' . request('type') : '' }}')"
            class="bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-all px-5 py-2.5 rounded-xl whitespace-nowrap">
            {{ $createLabel }}
        </x-primary-button>
    @endif

    <div class="relative w-full max-w-md">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </span>
        <input 
            x-model="search" 
            type="text" 
            placeholder="Rechercher dans le tableau..." 
            class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
        >
        <button x-show="search.length > 0" @click="search = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
        </button>
    </div>

</div>

       

    {{-- Conteneur du Tableau --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden w-full border border-gray-100 dark:border-gray-700">
        {{ $slot }}
    </div>

    {{-- Message "Aucun résultat" --}}
    <div x-show="search !== ''" x-cloak class="mt-4">
        <p class="text-sm text-gray-500 italic" x-text="'Résultats pour : ' + search"></p>
    </div>

    {{-- ... (le reste de ton code Modal et Scripts est identique) ... --}}

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
        // Gestion du clic simple pour la SÉLECTION visuelle
        row.addEventListener('click', (e) => {
            
            if (e.target.closest('button, a, form, input, select')) {
                return; 
            }

            rows.forEach(r => {
                r.classList.remove('bg-blue-50', 'dark:bg-blue-900/20', 'selected-row');
            });
            row.classList.add('bg-blue-50', 'dark:bg-blue-900/20', 'selected-row');
        });

        //  Gestion du double-clic pour l'ÉDITION
        row.addEventListener('dblclick', (e) => {
            if (e.target.closest('button, a, form, input, select')) return;

            const route = row.dataset.route;
            const id = row.dataset.id;
            if (!route || !id) return;
            const url = `/${route}/${id}/edit`; 
            openModal(url);
        });
    });

    //  Clic sur les boutons d'édition (crayon)
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

    // ---  FONCTION POUR LE SIGNE (+ ou -) ---
    // On la place ici pour qu'elle soit accessible partout dans initDevisForm
    const getMultiplicateur = () => {
        const typeField = container.querySelector('input[name="type_document"]');
        return (typeField && typeField.value === 'avoir') ? -1 : 1;
    };

    // ---  FONCTION DE CALCUL DES TOTAUX ---
    const updateTotals = () => {
        let totalHT = 0, totalTVA = 0, totalTTC = 0;
        const m = getMultiplicateur(); 

        tbody.querySelectorAll('tr').forEach(row => {
            const qte = parseFloat(row.querySelector('.quantite')?.value) || 0;
            const prix = parseFloat(row.querySelector('.prix')?.value) || 0;
            const tva = parseFloat(row.querySelector('.tva')?.value) || 0;
            
            const lineHT = qte * prix;
            const lineTVA = lineHT * (tva / 100);
            const lineTTC = lineHT + lineTVA;

            // Mise à jour du total de ligne avec le signe
            if(row.querySelector('.total')) {
                row.querySelector('.total').value = (lineTTC * m).toFixed(2);
            }
            
            totalHT += lineHT;
            totalTVA += lineTVA;
            totalTTC += lineTTC;
        });

        // Mise à jour des affichages visuels (spans)
        if(container.querySelector('#display_total_ht')) 
            container.querySelector('#display_total_ht').textContent = (totalHT * m).toFixed(2);
        
        if(container.querySelector('#display_total_tva')) 
            container.querySelector('#display_total_tva').textContent = (totalTVA * m).toFixed(2);
        
        const displayTTC = container.querySelector('#display_total_ttc');
        if(displayTTC) {
            displayTTC.textContent = (totalTTC * m).toFixed(2);
            // On applique le rouge si c'est un avoir
            displayTTC.style.color = (m === -1) ? '#ef4444' : ''; 
            displayTTC.classList.toggle('font-bold', m === -1);
        }
        
        // Mise à jour des inputs hidden pour Laravel
        if(container.querySelector('#total_ht')) container.querySelector('#total_ht').value = (totalHT * m).toFixed(2);
        if(container.querySelector('#total_tva')) container.querySelector('#total_tva').value = (totalTVA * m).toFixed(2);
        if(container.querySelector('#total_ttc')) container.querySelector('#total_ttc').value = (totalTTC * m).toFixed(2);
    };

    // --- GESTION DU CHOIX DE PRODUIT ET QUANTITÉ ---
    tbody.addEventListener('input', e => {
        if (e.target.classList.contains('produit-input')) {
            const val = e.target.value;
            const row = e.target.closest('tr');
            const option = Array.from(datalist.options).find(opt => opt.value === val);

            if (option) {
                row.querySelector('.description').value = option.dataset.designation || '';
                row.querySelector('.prix').value = option.dataset.prix || 0;
                row.querySelector('.tva').value = option.dataset.tva || 0;
                updateTotals();
            }
        }
        
        if (e.target.classList.contains('quantite')) {
            updateTotals();
        }
    });

    // ---  AJOUT ET SUPPRESSION DE LIGNES ---
    tbody.addEventListener('click', e => {
        if (e.target.closest('.removeRow')) {
            const rows = tbody.querySelectorAll('tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                updateTotals();
            }
        }

        if (e.target.closest('.addRowBelow')) {
            const currentRow = e.target.closest('tr');
            const newRow = currentRow.cloneNode(true);
            const index = tbody.querySelectorAll('tr').length;
            
            newRow.querySelectorAll('input').forEach(input => {
                input.value = input.classList.contains('quantite') ? 1 : '';
                if(input.name) {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                }
            });
            currentRow.after(newRow);
            updateTotals(); // Recalculer après ajout
        }
    });
  
    
    // Lancer un premier calcul au chargement (utile pour l'Edit)
    updateTotals();
}

        
</script>
@endpush
@endif


</div>