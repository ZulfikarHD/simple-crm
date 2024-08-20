<!-- Static sidebar for desktop -->
<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex w-64 flex-col">
        <div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-white">
            <div class="flex flex-1 flex-col overflow-y-auto pb-4 pt-5">
                <!-- Company Logo -->
                <div class="flex items-center px-4">
                    <img class="h-8 w-auto" src="{{ asset('images/logo.svg') }}" alt="Company Logo">
                </div>
                <!-- Navigation Menu -->
                <nav class="mt-5 flex-1 space-y-1 bg-white px-2">
                    <ul class="space-y-2">
                        {{-- Dashboard --}}
                        <li>
                            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                <i data-lucide="home" class="w-5 h-5 mr-2"></i>{{ __('Dashboard') }}
                            </x-nav-link>
                        </li>

                        {{-- Orders --}}
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full text-left text-gray-700 hover:bg-gray-200 px-2 py-2 rounded-lg transition duration-150 ease-in-out">
                                <i data-lucide="clipboard" class="w-5 h-5 mr-2"></i>{{ __('Manajemen Pesanan') }}
                                <svg :class="{ 'rotate-180': open }" class="ml-auto h-5 w-5 transition-transform transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="open" x-transition class="mt-2 space-y-2 pl-6">
                                <li>
                                    <x-nav-link href="{{ route('orders.create-order') }}" :active="request()->routeIs('orders.create-order')">
                                        {{ __('Buat Pesanan') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.index')">
                                        {{ __('Kelola Pesanan') }}
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>

                        {{-- Invoices --}}
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full text-left text-gray-700 hover:bg-gray-200 px-2 py-2 rounded-lg transition duration-150 ease-in-out">
                                <i data-lucide="credit-card" class="w-5 h-5 mr-2"></i>{{ __('Invoice dan Pembayaran') }}
                                <svg :class="{ 'rotate-180': open }" class="ml-auto h-5 w-5 transition-transform transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="open" x-transition class="mt-2 space-y-2 pl-6">
                                {{-- <li>
                                    <x-nav-link href="{{ route('invoices.create') }}" :active="request()->routeIs('invoices.create')">
                                        {{ __('Buat Faktur') }}
                                    </x-nav-link>
                                </li> --}}
                                <li>
                                    <x-nav-link href="{{ route('payments.create') }}" :active="request()->routeIs('payments.create')">
                                        {{ __('Pembayaran') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.index')">
                                        {{ __('Data Invoice') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('payments.index') }}" :active="request()->routeIs('payments.index')">
                                        {{ __('Data Pembayaran') }}
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>

                        {{-- Customers --}}
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full text-left text-gray-700 hover:bg-gray-200 px-2 py-2 rounded-lg transition duration-150 ease-in-out">
                                <i data-lucide="users" class="w-5 h-5 mr-2"></i>{{ __('Manajemen Pelanggan') }}
                                <svg :class="{ 'rotate-180': open }" class="ml-auto h-5 w-5 transition-transform transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="open" x-transition class="mt-2 space-y-2 pl-6">
                                <li>
                                    <x-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.index')">
                                        {{ __('Daftar Customer') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('customers.create') }}" :active="request()->routeIs('customers.create')">
                                        {{ __('Tambah Customer') }}
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>

                        {{-- Inventory --}}
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full text-left text-gray-700 hover:bg-gray-200 px-2 py-2 rounded-lg transition duration-150 ease-in-out">
                                <i data-lucide="box" class="w-5 h-5 mr-2"></i>{{ __('Inventaris') }}
                                <svg :class="{ 'rotate-180': open }" class="ml-auto h-5 w-5 transition-transform transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="open" x-transition class="mt-2 space-y-2 pl-6">
                                <li>
                                    <x-nav-link href="{{ route('inventory.index') }}" :active="request()->routeIs('inventory.index')">
                                        {{ __('Daftar Inventory') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('inventory.create') }}" :active="request()->routeIs('inventory.create')">
                                        {{ __('Tambah Item Inventory') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')">
                                        {{ __('Manajemen Supplier') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('purchase-orders.index') }}" :active="request()->routeIs('purchase-orders.index')">
                                        {{ __('Purchase Order') }}
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>

                        {{-- Reports --}}
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full text-left text-gray-700 hover:bg-gray-200 px-2 py-2 rounded-lg transition duration-150 ease-in-out">
                                <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>{{ __('Laporan') }}
                                <svg :class="{ 'rotate-180': open }" class="ml-auto h-5 w-5 transition-transform transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="open" x-transition class="mt-2 space-y-2 pl-6">
                                <li>
                                    <x-nav-link href="{{ route('reports.sales') }}" :active="request()->routeIs('reports.sales')">
                                        {{ __('Laporan Penjualan') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('reports.customers') }}" :active="request()->routeIs('reports.customers')">
                                        {{ __('Laporan Customer') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('reports.inventory') }}" :active="request()->routeIs('reports.inventory')">
                                        {{ __('Laporan Inventory') }}
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>

                        {{-- Settings --}}
                        <li x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full text-left text-gray-700 hover:bg-gray-200 px-2 py-2 rounded-lg transition duration-150 ease-in-out">
                                <i data-lucide="settings" class="w-5 h-5 mr-2"></i>{{ __('Pengaturan') }}
                                <svg :class="{ 'rotate-180': open }" class="ml-auto h-5 w-5 transition-transform transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="open" x-transition class="mt-2 space-y-2 pl-6">
                                <li>
                                    <x-nav-link href="{{ route('settings.index') }}" :active="request()->routeIs('settings.index')">
                                        {{ __('Setting') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('user-management.index') }}" :active="request()->routeIs('user-management.index')">
                                        {{ __('User Management') }}
                                    </x-nav-link>
                                </li>
                                <li>
                                    <x-nav-link href="{{ route('user-roles.index') }}" :active="request()->routeIs('user-roles.index')">
                                        {{ __('Role Management') }}
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
