@extends('layouts.table') 
@section('table') 
<table id="users-table" class="min-w-full w-full border border-gray-200"> 
    <thead class="bg-gray-100"> 
        <tr> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center"> ID</th>
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Email</th> 
            <th class="px-6 py-3 border-b border-r border-gray-300 text-center">Nom</th> 
        </tr>
    </thead>
    <tbody> @foreach ($users as $user) 
        <tr data-id="{{ $user->id }}" data-route="users" class="cursor-pointer w-full hover:bg-blue-100"> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $user->id }}</td> 
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ $user->email ?? '—' }}</td>
            <td class="px-6 py-4 border-b border-r border-gray-300">{{ ucfirst($user->name) }}</td> 
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

