<x-app-layout>
    <div class="container mx-auto p-6 bg-gray-100 ">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6">Tambah Pelanggan</h1>
        <form action="{{ route('customers.store') }}" method="POST" class="bg-white shadow-lg rounded-lg p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-3 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="address">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-3 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('address')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="phone_number">
                        Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-3 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('phone_number')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-3 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profil Media Sosial -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="social_media_profile">
                        Profil Media Sosial
                    </label>
                    <input type="text" name="social_media_profile" id="social_media_profile" value="{{ old('social_media_profile') }}" class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-3 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('social_media_profile')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Umpan Balik -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="feedback">
                        Umpan Balik
                    </label>
                    <textarea name="feedback" id="feedback" class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-3 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('feedback') }}</textarea>
                    @error('feedback')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Poin Loyalitas -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="loyalty_points">
                        Poin Loyalitas
                    </label>
                    <input type="number" name="loyalty_points" id="loyalty_points" value="{{ old('loyalty_points') }}" class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-3 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" min="0">
                    @error('loyalty_points')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan
                </button>
                <a href="{{ route('customers.index') }}" class="text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
