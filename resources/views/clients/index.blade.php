<x-layouts.app>
    <x-slot name="navigation">
        <x-navigation></x-navigation>
    </x-slot>
    <x-layouts.table createRoute="clients.create" createLabel="Ajouter un client">

        <table id="clients-table" class="min-w-full w-full border border-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100"></th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Code Client
                    </th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Société</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Type</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Téléphone</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Email</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Adresse</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Code Postal</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Ville</th>
                    <th class="px-6 py-3 border-b border-gray-300 text-center text-gray-900 dark:text-gray-100">Mode de règlement</th>
                </tr>
            </thead>

            <tbody class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
                @foreach ($clients as $client)
                    <tr data-id="{{ $client->id }}" data-route="clients" class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-700 even:bg-gray-50 dark:even:bg-gray-900">
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">
                         <x-action-buttons 
                            :id="$client->id"
                            :edit-url="route('clients.edit', $client->id)"
                            :delete-route="route('clients.destroy', $client->id)"
                            delete-label=" le client n° {{ $client->code_cli }}"
                        />
                        </td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->code_cli }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->societe }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ ucfirst($client->type) }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->telephone ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->email ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->adresse1 ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->code_postal ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-center border-gray-300 dark:border-gray-700">{{ $client->ville ?? '—' }}</td>
                        <td class="px-6 py-4 border-b text-end border-gray-300 dark:border-gray-700">
                            {{ match($client->reglement ?? '') {
                                'virement' => 'Virement',
                                'cheque' => 'Chèque',
                                'especes' => 'Espèces',
                                default => '—'
                            } }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>





    </x-layouts.table>
</x-layouts.app>
