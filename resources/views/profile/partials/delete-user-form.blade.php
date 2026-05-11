<div>
    <p class="text-sm text-gray-500 mb-4">
        Setelah akun dihapus, semua data akan hilang permanen dan tidak bisa dikembalikan.
    </p>

    <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                placeholder="Masukkan password untuk konfirmasi"
                class="w-full border border-red-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500">
            @error('password', 'userDeletion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit" 
                onclick="return confirm('Yakin ingin menghapus akun? Data tidak bisa dikembalikan!')"
                class="bg-red-600 text-white px-6 py-2.5 rounded-xl hover:bg-red-700 transition-all font-medium">
            🗑️ Hapus Akun
        </button>
    </form>
</div>