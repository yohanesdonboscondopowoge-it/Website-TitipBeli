<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - TitipBeli | @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 dark:bg-gray-950 text-white flex-shrink-0 flex flex-col">
            <div class="p-6 border-b border-gray-800">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-extrabold text-white">
                    🧳 TitipBeli
                </a>
                <p class="text-gray-400 text-xs mt-1">Admin Panel</p>
            </div>
            
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-all
                   {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white border-l-4 border-blue-500' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.pending-payments') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-all
                   {{ request()->routeIs('admin.pending-payments') ? 'bg-gray-800 text-white border-l-4 border-yellow-500' : '' }}">
                    💰 Verifikasi Bayar
                </a>
                <a href="{{ route('admin.disputes') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-all
                   {{ request()->routeIs('admin.disputes') ? 'bg-gray-800 text-white border-l-4 border-red-500' : '' }}">
                    🚨 Dispute
                </a>
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center gap-3 px-6 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-all
                   {{ request()->routeIs('admin.users') ? 'bg-gray-800 text-white border-l-4 border-green-500' : '' }}">
                    👥 Users
                </a>
                <hr class="my-4 border-gray-700 mx-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-6 py-3 text-sm text-gray-400 hover:text-white transition-all">
                    ← Kembali ke Site
                </a>
                <div class="px-6 py-4 border-t border-gray-700 mt-auto">
                    <x-dark-mode-toggle />
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
</body>
</html>