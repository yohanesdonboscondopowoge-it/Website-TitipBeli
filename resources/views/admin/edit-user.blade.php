@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('admin.users') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900">✏️ Edit User</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->username }}</p>
                <div class="mt-3 flex items-center justify-center gap-2">
                    <span class="font-bold {{ $user->trust_score >= 50 ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $user->trust_score }} TP
                    </span>
                    <span class="text-gray-300">|</span>
                    <span class="text-yellow-500">⭐ {{ number_format($user->rating_avg, 1) }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">Bergabung {{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Forms -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- Edit Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Informasi User</h3>
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Admin</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_ktp_verified" value="1" {{ $user->is_ktp_verified ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">KTP Verified</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_phone_verified" value="1" {{ $user->is_phone_verified ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-700">Phone Verified</span>
                        </label>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 font-medium transition-all">
                        💾 Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Reset Password -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">🔒 Reset Password</h3>
                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                    <button type="submit" class="bg-orange-600 text-white px-6 py-2.5 rounded-xl hover:bg-orange-700 font-medium transition-all">
                        🔒 Reset Password
                    </button>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-6">
                <h3 class="text-lg font-bold text-red-600 mb-4">⚠️ Danger Zone</h3>
                <div class="flex gap-3">
                    <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="px-6 py-2.5 border rounded-xl font-medium transition-all
                            {{ ($user->is_active ?? true) 
                                ? 'border-yellow-500 text-yellow-600 hover:bg-yellow-50' 
                                : 'border-green-500 text-green-600 hover:bg-green-50' }}">
                            {{ ($user->is_active ?? true) ? '🚫 Nonaktifkan User' : '✅ Aktifkan User' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                          onsubmit="return confirm('Yakin hapus permanen user {{ $user->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-6 py-2.5 border border-red-500 text-red-600 rounded-xl hover:bg-red-50 font-medium transition-all">
                            🗑️ Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection