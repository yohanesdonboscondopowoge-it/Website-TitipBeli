<div>
    <p class="text-sm text-gray-500 mb-4">
        Perbarui informasi profil dan email akunmu.
    </p>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm">
                Email kamu belum diverifikasi. 
                <button form="send-verification" class="text-primary-600 hover:underline font-medium">
                    Klik di sini untuk kirim ulang verifikasi.
                </button>
            </div>
        @endif

        <button type="submit" class="bg-gradient-to-r from-primary-600 to-purple-600 text-white px-6 py-2.5 rounded-xl hover:shadow-lg hover:shadow-primary-500/25 transition-all font-medium">
            💾 Simpan
        </button>
    </form>
</div>