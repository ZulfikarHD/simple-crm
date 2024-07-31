<!-- Static sidebar for desktop -->
<div class="hidden md:flex md:flex-shrink-0">
	<div class="flex w-64 flex-col">
		<div class="flex min-h-0 flex-1 flex-col border-r border-gray-200 bg-white">
			<div class="flex flex-1 flex-col overflow-y-auto pb-4 pt-5">
				<nav class="mt-5 flex-1 space-y-1 bg-white px-2">
					<!-- Sidenav Item -->
					<ul class="space-y-2 px-2">
                        {{-- Dashboard --}}
						<x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
							<i data-lucide="home" class="w-4 h-4 mr-2"></i>{{ __('Dashboard') }}
						</x-nav-link>

                        {{-- Customer --}}
						<x-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customer.index')">
							<i data-lucide="users" class="w-4 h-4 mr-2"></i>{{ __('Manajemen Pelanggan') }}
						</x-nav-link>

                        {{-- Sales --}}
						<x-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.index')">
							<i data-lucide="clipboard" class="w-4 h-4 mr-2"></i>{{ __('Manajemen Pesanan') }}
						</x-nav-link>

                        {{-- Invoices --}}
						<x-nav-link href="{{ route('invoices.index') }}" :active="request()->routeIs('invoices.index')">
							<i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>{{ __('Faktur dan Pembayaran') }}
						</x-nav-link>

                        {{-- Inventory --}}
						<x-nav-link href="{{ route('inventory.index') }}" :active="request()->routeIs('inventory.index')">
							<i data-lucide="box" class="w-4 h-4 mr-2"></i>{{ __('Inventaris') }}
						</x-nav-link>

                        {{-- Report --}}
						<x-nav-link href="{{ route('report.index') }}" :active="request()->routeIs('report.index')">
							<i data-lucide="file-text" class="w-4 h-4 mr-2"></i>{{ __('Laporan') }}
						</x-nav-link>

                        {{-- Teknisi --}}
						{{-- <x-nav-link href="{{ route('teknisi.index') }}" :active="request()->routeIs('teknisi.index')">
							<i data-lucide="tool" class="w-4 h-4 mr-2"></i>Teknisi
						</x-nav-link> --}}

                        {{-- Settings --}}
						<x-nav-link href="{{ route('setting.index') }}" :active="request()->routeIs('setting.index')">
							<i data-lucide="settings" class="w-4 h-4 mr-2"></i>{{ __('Settings') }}
						</x-nav-link>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>
