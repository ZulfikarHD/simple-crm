<x-app-layout>
    <div class="container mx-auto p-6 bg-gray-100 rounded-lg shadow-md">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6">Edit Pelanggan</h1>
        <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="bg-white shadow-lg rounded-lg p-6">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                    Nama <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" class="shadow appearance-none border border-gray-300 rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $customer->name }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="address">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="address" id="address" class="shadow appearance-none border border-gray-300 rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $customer->address }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="phone_number">
                    Nomor Telepon <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone_number" id="phone_number" class="shadow appearance-none border border-gray-300 rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $customer->phone_number }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email" class="shadow appearance-none border border-gray-300 rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $customer->email }}" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="social_media_profile">
                    Profil Media Sosial
                </label>
                <input type="text" name="social_media_profile" id="social_media_profile" class="shadow appearance-none border border-gray-300 rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $customer->social_media_profile }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="feedback">
                    Umpan Balik
                </label>
                <textarea name="feedback" id="feedback" class="shadow appearance-none border border-gray-300 rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $customer->feedback }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="loyalty_points">
                    Poin Loyalitas
                </label>
                <input type="number" name="loyalty_points" id="loyalty_points" class="shadow appearance-none border border-gray-300 rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $customer->loyalty_points }}" min="0">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
