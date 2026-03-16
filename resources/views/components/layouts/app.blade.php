<!DOCTYPE html>
<html lang="fr" 
   x-data="{ dark: localStorage.getItem('dark') === 'true' || (!localStorage.getItem('dark') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" 
    :class="{ 'dark': dark }" 
     x-init="$watch('dark', val => localStorage.setItem('dark', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGestion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    @isset($navigation)
        {{ $navigation }}
    @endisset

    @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif
            @if(isset($fullscreen))
            {{ $slot }}
        @else
            <main class="max-w-full py-8 px-4">
                {{ $slot }}
            </main>
        @endif


    @stack('scripts')

</body>
</html>