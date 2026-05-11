@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900">👥 Users</h1>
    <p class="text-gray-500">{{ $users->total() }} user terdaftar</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trust</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">@{{ $user->username }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400">{{ $user->phone ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-bold {{ $user->trust_score >= 50 ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $user->trust_score }}
                        </span>
                        <span class="text-xs text-gray-400 ml-1">⭐ {{ number_format($user->rating_avg, 1) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($user->is_admin)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                Admin
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                User
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($user->is_active ?? true)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                    class="p-2 {{ ($user->is_active ?? true) ? 'text-yellow-600 hover:bg-yellow-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-colors"
                                    title="{{ ($user->is_active ?? true) ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Yakin hapus user {{ $user->name }}? Data tidak bisa dikembalikan!')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                        👥 Belum ada user
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
</div>
@endsection