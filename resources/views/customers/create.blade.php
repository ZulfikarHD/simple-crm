<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Tambah Pelanggan Baru</h2>
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nama</label>
                <input type="text" name="name" id="name" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700">Alamat</label>
                <input type="text" name="address" id="address" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-4">
                <label for="phone_number" class="block text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
        </form>
    </div>
</x-app-layout>
