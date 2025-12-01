<x-layouts.app>
    <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>
<x-layouts.table createRoute="documents.create" createLabel="Ajouter un avoir">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($documents->isEmpty())
        <div class="alert alert-info">Aucun avoir enregistré pour le moment.</div>
    @else
        <table class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 border-b text-center dark:text-gray-100"></th>
                    <th class="px-6 py-3 border-b text-center dark:text-gray-100">Code avoir</th>
    
                    <th class="px-6 py-3 border-b text-center dark:text-gray-100">Date</th>
                    <th class="px-6 py-3 border-b text-center dark:text-gray-100">Client</th>
                    <th class="px-6 py-3 border-b text-end dark:text-gray-100">Total TTC (€)</th>
                </tr>
            </thead>
          <tbody class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            @foreach($documents as $document)
                <tr data-id="{{ $document->id }}" data-route="documents" class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">
                        <x-action-buttons 
                            :id="$document->id"
                            :edit-url="route('documents.edit', $document->id)"
                        />
                    </td>
                    <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $document->code_document }}</td>
                    <!-- <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ ucfirst($document->type_document) }}</td> -->
                    <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $document->date_document }}</td>
                    <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $document->client_nom }}</td>
                    <td class="px-6 py-4 border-b text-end border-gray-300 dark:border-gray-700">{{ number_format($document->total_ttc, 2, ',', ' ') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

</x-layouts.table>
</x-layouts.app>
