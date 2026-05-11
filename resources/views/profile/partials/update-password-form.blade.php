<div>
    <p class="text-sm text-gray-500 mb-4">
        Pastikan menggunakan password yang panjang dan acak untuk keamanan.
    </p>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
            <input type="password" name="current_password" required
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            @error('current_password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
            <input type="password" name="password" required
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" required
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            @error('password_confirmation')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="bg-gradient-to-r from-primary-600 to-purple-600 text-white px-6 py-2.5 rounded-xl hover:shadow-lg hover:shadow-primary-500/25 transition-all font-medium">
            🔒 Update Password
        </button>
    </form>
</div>