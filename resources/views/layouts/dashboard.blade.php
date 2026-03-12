<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Perpus Digital')</title>
</head>
<body class=" bg-stone-100 text-stone-900">
    <div class="flex">
        <x-sidebar />

        <main class="flex-1 bg-stone-50">
            <div class="px-6 py-6 lg:px-12 lg:py-10">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
