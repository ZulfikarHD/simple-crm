<x-app-layout>
    <div>
        <h1 class="text-3xl font-bold mb-6">Pengaturan</h1>
        @if(session('success'))
            <div class="bg-green-500 text-white py-2 px-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form action="" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="app_name">
                    Nama Aplikasi
                </label>
                <input type="text" name="app_name" id="app_name" value="{{ $settings['app_name'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="app_email">
                    Email Aplikasi
                </label>
                <input type="email" name="app_email" id="app_email" value="{{ $settings['app_email'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="app_timezone">
                    Zona Waktu
                </label>
                <input type="text" name="app_timezone" id="app_timezone" value="{{ $settings['app_timezone'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="app_locale">
                    Bahasa Aplikasi
                </label>
                <input type="text" name="app_locale" id="app_locale" value="{{ $settings['app_locale'] ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
