@props([
    'createRoute' => null,
    'createLabel' => 'Ajouter',
])

<div class="w-full p-6">

    {{-- En-tête : titre + boutons --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
        @if($createRoute)
            <div class="flex gap-3">
                <x-primary-button
                    type="button"
                    onclick="openCreateModal('{{ route($createRoute) }}')"
                    class="bg-blue-600 hover:bg-blue-700 text-white">
                    {{ $createLabel }}
                </x-primary-button>

                {{-- Bouton Supprimer --}}
                <form id="delete-form" method="POST" action="#" class="hidden" onsubmit="return confirm('Supprimer cet élément ?')">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit" class="bg-red-600 hover:bg-red-700 text-white">
                        Supprimer
                    </x-danger-button>
                </form>
            </div>
        @endif
    </div>

    {{-- Tableau --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto w-full">
        {{ $slot }}
    </div>

    {{-- Modal création/édition --}}
    <div id="createModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl relative flex flex-col max-h-[80vh]">

            <button onclick="closeCreateModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 font-bold text-xl z-10">✖</button>

            <div id="create-form-container" class="overflow-auto p-6 flex-1 space-y-4"></div>

            <div class="flex justify-end gap-3 p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <x-secondary-button type="button" onclick="closeCreateModal()">Annuler</x-secondary-button>
                <x-primary-button type="submit" form="document-form"> Enregistrer</x-primary-button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// --- Modal fetch ---
function openCreateModal(url) {
    fetch(url)
        .then(res => res.ok ? res.text() : Promise.reject('Erreur '+res.status))
        .then(html => {
            const container = document.getElementById('create-form-container');
            container.innerHTML = html;

            // Ajout des classes Tailwind
            container.querySelectorAll('input, select, textarea').forEach(el => {
                if (!el.classList.contains('rounded-md')) {
                    el.classList.add('w-full','rounded-md','border-gray-300','dark:border-gray-600','bg-white','dark:bg-gray-800','text-gray-900','dark:text-gray-100','shadow-sm','px-2','py-1');
                }
            });
            container.querySelectorAll('label').forEach(lb => {
                if (!lb.classList.contains('block')) {
                    lb.classList.add('block','text-sm','font-medium','text-gray-700','dark:text-gray-300','mb-1');
                }
            });

            initDevisForm(container); // <-- Initialisation du formulaire

            document.getElementById('createModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        })
        .catch(err => {
            console.error('Erreur chargement formulaire :', err);
            alert('Erreur lors du chargement du formulaire.');
        });
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// --- Fonction d'initialisation du formulaire de devis ---
function initDevisForm(container) {
    const tbody = container.querySelector('#lignesTable tbody');
    const datalist = container.querySelector('#produits');
    if (!tbody || !datalist) return;

    function updatePrixTTC(row) {
        const qte = parseFloat(row.querySelector('.quantite').value)||0;
        const prix = parseFloat(row.querySelector('.prix').value)||0;
        const tva = parseFloat(row.querySelector('.tva').value)||0;
        row.querySelector('.total').value = (prix*qte*(1+tva/100)).toFixed(2);
    }

    function resetRow(row) {
        const input = row.querySelector('.produit-input');
        input.value = ''; input.setAttribute('list','produits');
        row.querySelector('.description').value = '';
        row.querySelector('.quantite').value = 1;
        row.querySelector('.prix').value = '';
        row.querySelector('.tva').value = '';
        row.querySelector('.total').value = '';
        updatePrixTTC(row);
    }

    function updateAllNames() {
        tbody.querySelectorAll('tr').forEach((row,i)=>{
            row.querySelector('.produit-input').setAttribute('name',`lignes[${i}][produit_code]`);
            row.querySelector('.description').setAttribute('name',`lignes[${i}][description]`);
            row.querySelector('.quantite').setAttribute('name',`lignes[${i}][quantite]`);
            row.querySelector('.prix').setAttribute('name',`lignes[${i}][prix_unitaire_ht]`);
            row.querySelector('.tva').setAttribute('name',`lignes[${i}][taux_tva]`);
            row.querySelector('.total').setAttribute('name',`lignes[${i}][total_ttc]`);
        });
        updateTotals();
    }

    function updateTotals() {
        let totalHT=0, totalTVA=0, totalTTC=0;
        tbody.querySelectorAll('tr').forEach(row=>{
            const qte=parseFloat(row.querySelector('.quantite').value)||0;
            const prix=parseFloat(row.querySelector('.prix').value)||0;
            const tva=parseFloat(row.querySelector('.tva').value)||0;
            const ht = qte*prix;
            const tv = ht*tva/100;
            totalHT+=ht; totalTVA+=tv; totalTTC+=ht+tv;
        });
        container.querySelector('#total_ht').value=totalHT.toFixed(2);
        container.querySelector('#total_tva').value=totalTVA.toFixed(2);
        container.querySelector('#total_ttc').value=totalTTC.toFixed(2);
        container.querySelector('#display_total_ht').textContent=totalHT.toFixed(2)+' €';
        container.querySelector('#display_total_tva').textContent=totalTVA.toFixed(2)+' €';
        container.querySelector('#display_total_ttc').textContent=totalTTC.toFixed(2)+' €';
    }

    tbody.addEventListener('input',e=>{
        const row=e.target.closest('tr');
        if(e.target.classList.contains('produit-input')){
            const code=e.target.value.trim();
            let option=null;
            for(let opt of datalist.options){ if(opt.value===code){option=opt; break;} }
            if(option){
                row.querySelector('.description').value=option.getAttribute('data-designation')||'';
                row.querySelector('.prix').value=option.getAttribute('data-prix')||'';
                row.querySelector('.tva').value=option.getAttribute('data-tva')||'';
                updatePrixTTC(row); updateTotals();
            } else resetRow(row);
        }
        if(e.target.classList.contains('quantite') || e.target.classList.contains('tva')){ updatePrixTTC(row); updateTotals(); }
    });

    tbody.addEventListener('click',e=>{
        const row=e.target.closest('tr');
        if(e.target.classList.contains('addRowBelow')){
            const newRow=row.cloneNode(true); resetRow(newRow); row.insertAdjacentElement('afterend',newRow); updateAllNames();
        }
        if(e.target.classList.contains('removeRow')){
            if(tbody.querySelectorAll('tr').length>1){ row.remove(); updateAllNames(); }
        }
    });

    updateAllNames();
}
</script>
@endpush
