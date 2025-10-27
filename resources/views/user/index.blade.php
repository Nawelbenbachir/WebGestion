<x-guest-layout>
    
    <h1>Liste des utilisateurs</h1>
     <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
            </tr>
        </thead>
        @foreach ($users as $user)
                <tbody>
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach

</x-guest-layout>
