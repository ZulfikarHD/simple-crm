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
							{{ __('Dashboard') }}
						</x-nav-link>

                        {{-- Customer --}}
						<x-nav-link href="{{ route('customer.index') }}" :active="request()->routeIs('customer.index')">
							{{ __('Manajemen Pelanggan') }}
						</x-nav-link>

                        {{-- Service --}}
						<x-nav-link href="{{ route('service.index') }}" :active="request()->routeIs('service.index')">
							{{ __('Manajemen Layanan') }}
						</x-nav-link>

                        {{-- Sales --}}
						<x-nav-link href="{{ route('sales.index') }}" :active="request()->routeIs('sales.index')">
							{{ __('Manajemen Pesanan') }}
						</x-nav-link>

                        {{-- Payment --}}
						<x-nav-link href="{{ route('payment.index') }}" :active="request()->routeIs('payment.index')">
							{{ __('Faktur dan Pembayaran') }}
						</x-nav-link>

                        {{-- Inventory --}}
						<x-nav-link href="{{ route('inventory.index') }}" :active="request()->routeIs('inventory.index')">
							{{ __('Inventaris') }}
						</x-nav-link>

                        {{-- Report --}}
						<x-nav-link href="{{ route('report.index') }}" :active="request()->routeIs('report.index')">
							{{ __('Laporan') }}
						</x-nav-link>

                        {{-- Teknisi --}}
						{{-- <x-nav-link href="{{ route('teknisi.index') }}" :active="request()->routeIs('teknisi.index')">
							Teknisi
						</x-nav-link> --}}

                        {{-- Settings --}}
						<x-nav-link href="{{ route('setting.index') }}" :active="request()->routeIs('setting.index')">
							{{ __('Settings') }}
						</x-nav-link>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>
