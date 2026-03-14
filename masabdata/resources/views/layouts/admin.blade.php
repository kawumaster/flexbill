<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | MASABDATA</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white">
        <div class="p-4 text-xl font-bold border-b border-gray-700">
            MASABDATA Adminn
        </div>
        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block p-2 rounded hover:bg-gray-700">Dashboard</a>
            <a href="{{ route('admin.users') }}" class="block p-2 rounded hover:bg-gray-700">Users</a>
            <a href="#" class="block p-2 rounded hover:bg-gray-700">Transactions</a>
            <a href="#" class="block p-2 rounded hover:bg-gray-700">Services</a>
        </nav>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

</div>
</body>
</html>
