@php
    $isVueseule = (bool) request('vueseule');
@endphp

@if ($isVueseule)
    {{-- Contexte : Vue complète --}}
    <x-layouts.app>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Créer une nouvelle Société
            </h2>
        </x-slot>
        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8  bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                
                {{-- Appel du composant, en passant la propriété --}}
                <x-societes.societe-form :isVueseule="$isVueseule" />
                
            </div> 
        </div>
    </x-layouts.app> 
@else
    {{-- Contexte : Modale (Rendu seul) --}}
    
    {{-- Appel du composant, en passant la propriété --}}
    <x-societes.societe-form :isVueseule="$isVueseule" />
@endif