<x-layouts.app>
     <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>

    <div class="py-6"  
        x-data="{ 
        currentTab: (new URLSearchParams(window.location.search)).get('tab') || 
                    (window.location.hash ? window.location.hash.replace('#', '') : 'societe') 
     }">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            {{-- ZONE DES MESSAGES (Placée ICI pour être toujours visible) --}}
            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                <div class="flex border-b border-gray-200 dark:border-gray-700">
    
    {{-- Onglet Société --}}
    <button @click="currentTab = 'societe'; window.location.hash = 'societe'; closeModal()" 
            :class="{ 
                // CLASSE ACTIVE: En mode clair (bleu foncé)
                'border-b-2 border-indigo-500 text-indigo-600 font-semibold': currentTab === 'societe', 
                
                // AJUSTEMENT POUR LE MODE SOMBRE: Surcharger le texte en gris très clair et ajouter un fond subtil.
                'dark:text-gray-100 dark:bg-gray-700/50': currentTab === 'societe', 

                // CLASSE INACTIVE: (couleurs neutres pour les deux modes)
                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700/50': currentTab !== 'societe' 
            }"
            class="py-4 px-6 block appearance-none focus:outline-none transition duration-150 ease-in-out">
        Gestion des sociétés
    </button>
    
    {{-- Onglet Utilisateurs --}}
    <button @click="currentTab = 'utilisateur'; window.location.hash = 'utilisateur'; closeModal()" 
            :class="{ 
                // CLASSE ACTIVE: En mode clair (bleu foncé)
                'border-b-2 border-indigo-500 text-indigo-600 font-semibold': currentTab === 'utilisateur', 

                // AJUSTEMENT POUR LE MODE SOMBRE: Surcharger le texte en gris très clair et ajouter un fond subtil.
                'dark:text-gray-100 dark:bg-gray-700/50': currentTab === 'utilisateur', 

                // CLASSE INACTIVE: (couleurs neutres pour les deux modes)
                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700/50': currentTab !== 'utilisateur' 
            }"
            class="py-4 px-6 block appearance-none focus:outline-none transition duration-150 ease-in-out">
        Gestion des utilisateurs
    </button>
</div>

                {{-- Contenu des Onglets --}}
<div class="p-6 lg:p-8 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
    
    {{-- Bloc 1: Paramètres Société --}}
    <div x-show="currentTab === 'societe'">
        {{-- On ajoute l'attribut hideModal="true" pour que le composant n'affiche pas sa propre modal --}}
        <x-layouts.table createRoute="societes.create" createLabel="Ajouter une société" hideModal="true">
            @include('societe.index') 
        </x-layouts.table>
    </div>

    {{-- Bloc 2: Gestion des Utilisateurs --}}
    <div x-show="currentTab === 'utilisateur'">
        {{-- Pareil ici : hideModal="true" --}}
        <x-layouts.table createRoute="user.create" createLabel="Ajouter un utilisateur" hideModal="true">
            @include('user.index')
        </x-layouts.table>
    </div>
</div>

            </div>
        </div>
    </div>
   {{-- Modal unique pour create/edit --}}
<div id="modal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-5xl relative flex flex-col max-h-[90vh]">

        <!-- Bouton fermer -->
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <!-- Contenu dynamique -->
        <div id="modal-content" class="overflow-y-auto p-6 flex-1 space-y-4"></div>

    </div>
</div>


<script>


//  Double-clic sur les lignes (Délégation)
document.addEventListener('dblclick', (e) => {
    const row = e.target.closest('tr[data-id]');
    if (row) {
        const route = row.dataset.route;
        const id = row.dataset.id;
        if (route && id) {
            openModal(`/${route}/${id}/edit`);
        }
    }
});

function closeModal() {
    const modal = document.getElementById('modal');
    const container = document.getElementById('modal-content');
    
    if (modal) modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    
    if (container) {
        // On attend la fin de l'animation de fermeture pour vider
        setTimeout(() => { container.innerHTML = ''; }, 200);
    }
}

// Fonction d'ouverture globale
function openModal(url) {
    const container = document.getElementById('modal-content');
    if (!container) return;

    // Loader
    container.innerHTML = '<div class="flex justify-center p-12"><svg class="animate-spin h-8 w-8 text-blue-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
    
    document.getElementById('modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');

    fetch(url)
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
            
            // Stylisation des inputs (copié de ton composant table pour rester cohérent)
            container.querySelectorAll('input:not([type="checkbox"]), select, textarea').forEach(el => {
                el.classList.add(
                    'w-full','rounded-md','border-gray-300','dark:border-gray-600',
                    'bg-white','dark:bg-gray-800','text-gray-900','dark:text-gray-100',
                    'shadow-sm','focus:border-blue-500','focus:ring-blue-500'
                );
            });

            // Focus automatique après affichage
            setTimeout(() => {
                const firstInput = container.querySelector('input:not([type="hidden"]), select, textarea');
                if (firstInput) firstInput.focus();
            }, 100);

            
            if (container.querySelector('#lignesTable') && typeof initDevisForm === 'function') {
                initDevisForm(container);
            }
        })
        .catch(err => {
            console.error(err);
            closeModal();
        });
}

// Gestion des clics sur les lignes (Délégation d'événements)
document.addEventListener('dblclick', (e) => {
    const row = e.target.closest('tr[data-id]');
    if (row && !e.target.closest('button, a, form')) {
        const route = row.dataset.route;
        const id = row.dataset.id;
        if (route && id) openModal(`/${route}/${id}/edit`);
    }
});

document.addEventListener('click', (e) => {
    // A. Bouton MODIFIER (Crayon)
    const editBtn = e.target.closest('[data-edit-url]');
    if (editBtn) {
        e.preventDefault();
        e.stopPropagation();
        openModal(editBtn.dataset.editUrl);
        return;
    }

    // B. Bouton AJOUTER (Lien avec /create)
    const createBtn = e.target.closest('a[href*="/create"]');
    if (createBtn && !createBtn.hasAttribute('data-no-modal')) {
        e.preventDefault();
        e.stopPropagation();
        openModal(createBtn.getAttribute('href'));
        return;
    }

    // C. Bouton SUPPRIMER (Classe spécifique)
    const deleteBtn = e.target.closest('.delete-btn');
    if (deleteBtn) {
        if (!confirm('Voulez-vous vraiment supprimer cet élément ?')) {
            e.preventDefault();
            e.stopPropagation();
        }
        // Sinon, on laisse le formulaire se soumettre normalement
    }
});

// GESTION DU DOUBLE-CLIC SUR LES LIGNES
document.addEventListener('dblclick', (e) => {
    const row = e.target.closest('tr[data-id]');
    // On ne déclenche pas si on double-clique sur un bouton ou un lien
    if (row && !e.target.closest('button, a, form')) {
        const route = row.dataset.route;
        const id = row.dataset.id;
        if (route && id) {
            openModal(`/${route}/${id}/edit`);
        }
    }
});

//soumettre le formulaire actuellement chargé
function submitCurrentForm() {
    const container = document.getElementById('modal-content'); 
    if(!container) return;

    const form = container.querySelector('form');
    if(!form) return alert('Aucun formulaire trouvé !');

    const formData = new FormData(form);
    fetch(form.action, {
        method: form.method || 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
           
            window.location.reload();
            
        } else if(data.errors){
            console.log(data.errors);
            alert('Erreur : voir console.');
        }
    })
    .catch(err => { console.error(err); alert('Erreur lors de l\'enregistrement'); });

}
</script>
</x-layouts.app>