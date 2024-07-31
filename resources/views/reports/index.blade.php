
<x-app-layout>
    <div>
        <h1 class="text-3xl font-bold mb-6">Daftar Laporan</h1>
        <ul class="list-disc pl-6">
            <li class="mb-2">
                <a href="{{ route('reports.show', 'customers') }}" class="text-blue-500">Laporan Pelanggan</a>
            </li>
            <li class="mb-2">
                <a href="{{ route('reports.show', 'orders') }}" class="text-blue-500">Laporan Pesanan</a>
            </li>
            <li class="mb-2">
                <a href="{{ route('reports.show', 'inventories') }}" class="text-blue-500">Laporan Inventaris</a>
            </li>
            <li class="mb-2">
                <a href="{{ route('reports.show', 'invoices') }}" class="text-blue-500">Laporan Faktur</a>
            </li>
        </ul>
    </div>
</x-app-layout>
