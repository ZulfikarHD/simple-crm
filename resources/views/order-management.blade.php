<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pesanan') }}
        </h2>
    </x-slot>

    <div x-data="{ editModal: false, selectedOrder: {}, deleteModal: false, selectedOrderId: null }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div class="mt-8 text-2xl">
                            {{ __('Kelola Pesanan Layanan') }}
                        </div>

                        <div class="mt-6 text-gray-500">
                            {{ __('Lihat dan kelola pesanan layanan yang masuk, update status pesanan, dan atur jadwal teknisi.') }}
                        </div>
                    </div>

                    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
                        <!-- Tabel Pesanan -->
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">{{ __('Pesanan') }}</div>
                            </div>

                            <div class="ml-12">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('ID Pesanan') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Nama Pelanggan') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Jenis Layanan') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Status') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Teknisi') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Aksi') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- Contoh Baris Pesanan -->
                                        @foreach ($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $order->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->customer_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->service_type }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->status }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->technician }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button @click="editModal = true; selectedOrder = {{ $order }}" class="text-indigo-600 hover:text-indigo-900">
                                                    <i data-lucide="edit" class="w-5 h-5"></i>
                                                </button>
                                                <button @click="deleteModal = true; selectedOrderId = {{ $order->id }}" class="text-red-600 hover:text-red-900">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Update Status Pesanan dan Atur Jadwal Teknisi -->
                        <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
                            <div class="flex items-center">
                                <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">{{ __('Update Pesanan') }}</div>
                            </div>

                            <div class="ml-12">
                                <form action="{{ route('order.store') }}" method="POST">
                                    @csrf
                                    <div class="mt-4">
                                        <label for="order_id" class="block text-sm font-medium text-gray-700">{{ __('ID Pesanan') }}</label>
                                        <input type="text" name="order_id" id="order_id" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div class="mt-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                                        <select id="status" name="status" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <option>Pending</option>
                                            <option>Dalam Proses</option>
                                            <option>Selesai</option>
                                        </select>
                                    </div>

                                    <div class="mt-4">
                                        <label for="technician" class="block text-sm font-medium text-gray-700">{{ __('Teknisi') }}</label>
                                        <select id="technician" name="technician" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                            <option>Belum Ditugaskan</option>
                                            <option>Teknisi 1</option>
                                            <option>Teknisi 2</option>
                                        </select>
                                    </div>

                                    <div class="mt-6">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Update Pesanan') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="editModal" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="`/orders/${selectedOrder.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Pesanan</h3>
                                    <div class="mt-2">
                                        <div>
                                            <label for="editOrderId" class="block text-sm font-medium text-gray-700">ID Pesanan</label>
                                            <input type="text" name="order_id" id="editOrderId" x-model="selectedOrder.id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" readonly>
                                        </div>
                                        <div>
                                            <label for="editCustomerName" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                                            <input type="text" name="customer_name" id="editCustomerName" x-model="selectedOrder.customer_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div>
                                            <label for="editServiceType" class="block text-sm font-medium text-gray-700">Jenis Layanan</label>
                                            <input type="text" name="service_type" id="editServiceType" x-model="selectedOrder.service_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                        <div>
                                            <label for="editStatus" class="block text-sm font-medium text-gray-700">Status</label>
                                            <select id="editStatus" name="status" x-model="selectedOrder.status" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                <option>Pending</option>
                                                <option>Dalam Proses</option>
                                                <option>Selesai</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="editTechnician" class="block text-sm font-medium text-gray-700">Teknisi</label>
                                            <select id="editTechnician" name="technician" x-model="selectedOrder.technician" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                <option>Belum Ditugaskan</option>
                                                <option>Teknisi 1</option>
                                                <option>Teknisi 2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" @click="editModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div x-show="deleteModal" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="`/orders/${selectedOrderId}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Hapus Pesanan</h3>
                                    <div class="mt-2">
                                        <p>Apakah Anda yakin ingin menghapus pesanan ini?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                            <button type="button" @click="deleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
