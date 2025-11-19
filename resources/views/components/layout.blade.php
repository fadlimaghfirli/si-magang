<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        <!-- Navbar -->
        <x-navbar></x-navbar>

        <!-- Sidebar -->
        <x-sidebar></x-sidebar>

        <!-- Main content -->
        <main class="p-4 md:ml-72 min-h-screen py-20 bg-gray-50 dark:bg-gray-900">
            {{ $slot }}
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>

</html>
