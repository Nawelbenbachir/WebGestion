@php
    $isVueseule = (bool) request('vueseule');
@endphp

@if ($isVueseule)
    {{-- Contexte : Vue complète --}}
    <x-layouts.app>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
                Créer une nouvelle Société
            </h2>
        </x-slot>
      <div class="min-h-[calc(100vh-64px)] flex flex-col justify-center py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 w-full">
                
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
                    <x-societes.societe-form :isVueseule="true" />
                </div>
                
                {{-- Petit message d'aide optionnel sous le bloc --}}
                <p class="text-center text-gray-500 text-xs mt-4">
                    Ces informations seront utilisées pour vos documents légaux.
                </p>
            </div>
        </div>
    </x-layouts.app> 
@else
    {{-- Contexte : Modale (Rendu seul) --}}
    
    {{-- Appel du composant, en passant la propriété --}}
    <x-societes.societe-form :isVueseule="$isVueseule" />
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif