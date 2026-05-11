@extends('layouts.app')

@section('title', 'Edit Permintaan')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">✏️ Edit Permintaan</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('requests.update', $titipRequest) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang *</label>
                    <input type="text" name="item_name" value="{{ old('item_name', $titipRequest->item_name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('item_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" maxlength="1000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $titipRequest->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih...</option>
                        <option value="makanan" {{ old('category', $titipRequest->category) === 'makanan' ? 'selected' : '' }}>🍜 Makanan</option>
                        <option value="elektronik" {{ old('category', $titipRequest->category) === 'elektronik' ? 'selected' : '' }}>📱 Elektronik</option>
                        <option value="fashion" {{ old('category', $titipRequest->category) === 'fashion' ? 'selected' : '' }}>👕 Fashion</option>
                        <option value="dokumen" {{ old('category', $titipRequest->category) === 'dokumen' ? 'selected' : '' }}>📄 Dokumen</option>
                        <option value="lainnya" {{ old('category', $titipRequest->category) === 'lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kota Asal *</label>
                        <input type="text" name="origin_city" value="{{ old('origin_city', $titipRequest->origin_city) }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kota Tujuan *</label>
                        <input type="text" name="destination_city" value="{{ old('destination_city', $titipRequest->destination_city) }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Budget Min (Rp)</label>
                        <input type="number" name="budget_min" value="{{ old('budget_min', $titipRequest->budget_min) }}" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Budget Max (Rp)</label>
                        <input type="number" name="budget_max" value="{{ old('budget_max', $titipRequest->budget_max) }}" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                        <input type="date" name="deadline" value="{{ old('deadline', $titipRequest->deadline?->format('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi Berat</label>
                        <select name="weight_estimate" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih...</option>
                            <option value="ringan" {{ old('weight_estimate', $titipRequest->weight_estimate) === 'ringan' ? 'selected' : '' }}>🪶 Ringan</option>
                            <option value="sedang" {{ old('weight_estimate', $titipRequest->weight_estimate) === 'sedang' ? 'selected' : '' }}>📦 Sedang</option>
                            <option value="berat" {{ old('weight_estimate', $titipRequest->weight_estimate) === 'berat' ? 'selected' : '' }}>🏋️ Berat</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang</label>
                    @if($titipRequest->image)
                        <img src="{{ Storage::url($titipRequest->image) }}" class="w-32 h-32 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="image" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('requests.show', $titipRequest) }}" class="flex-1 text-center border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700">
                        💾 Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection