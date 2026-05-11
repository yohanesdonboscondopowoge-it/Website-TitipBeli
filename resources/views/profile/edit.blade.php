@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up" x-data="{ active: 'profile' }">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-8">⚙️ Pengaturan Profil</h1>

    <div class="space-y-4">
        
        <!-- Profile Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <button @click="active = 'profile'" 
                class="w-full flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-all">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">👤</span>
                    <div class="text-left">
                        <h2 class="text-lg font-bold text-gray-900">Informasi Profil</h2>
                        <p class="text-sm text-gray-500">{{ $user->name }} • {{ $user->email }}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="active === 'profile' ? 'rotate-180' : ''" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="active === 'profile'" x-transition class="px-6 pb-6 border-t border-gray-100 pt-4">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Password -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <button @click="active = 'password'" 
                class="w-full flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-all">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🔒</span>
                    <div class="text-left">
                        <h2 class="text-lg font-bold text-gray-900">Update Password</h2>
                        <p class="text-sm text-gray-500">Pastikan password kamu aman</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="active === 'password' ? 'rotate-180' : ''" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="active === 'password'" x-transition class="px-6 pb-6 border-t border-gray-100 pt-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
            <button @click="active = 'delete'" 
                class="w-full flex items-center justify-between px-6 py-4 hover:bg-red-50 transition-all">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🗑️</span>
                    <div class="text-left">
                        <h2 class="text-lg font-bold text-red-600">Hapus Akun</h2>
                        <p class="text-sm text-red-400">Tindakan ini tidak bisa dibatalkan</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-red-400 transition-transform" :class="active === 'delete' ? 'rotate-180' : ''" 
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="active === 'delete'" x-transition class="px-6 pb-6 border-t border-red-100 pt-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection