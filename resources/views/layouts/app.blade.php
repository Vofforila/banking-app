<!DOCTYPE html>
<html
    x-data="{ theme: $persist('default'), darkMode: $persist('dark') }"
    :data-theme="theme"
    :class="darkMode === 'dark' ? 'dark' : ''"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>
<head>
    {{--    @include('partials.head')--}}
    <script>
        (function () {
            const raw = localStorage.getItem('_x_theme');
            const theme = raw ? JSON.parse(raw) : 'default';
            document.documentElement.setAttribute('data-theme', theme);

        })();

        document.addEventListener('livewire:navigated', function () {
            const raw = localStorage.getItem('_x_theme');
            const theme = raw ? JSON.parse(raw) : 'default';
            document.documentElement.setAttribute('data-theme', theme);
        });
    </script>

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title>VOF Banking</title>

    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>


    @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/index.css'])
    @fluxAppearance


</head>
<body class="min-h-screen bg-white dark:bg-zinc-800" antialiased>
<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
</body>
</html>
