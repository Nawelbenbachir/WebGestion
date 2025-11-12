<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGestion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">

    <x-navigation>
    </x-navigation>

    <main class="max-w-full py-8 px-4">
       {{ $slot }}
    </main>
    <!-- pour exécuter les scripts -->
    @stack('scripts') 
</body>
</html>

