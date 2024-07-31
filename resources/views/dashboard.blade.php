<x-app-layout>

	<div class="container mx-auto px-4 py-8">
		<h1 class="mb-4 text-2xl font-bold">Dashboard</h1>

		<!-- Statistik Utama -->
		<div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-3">
			<div class="rounded-lg bg-white p-6 shadow-lg">
				<h2 class="mb-2 text-xl font-semibold">Total Penjualan</h2>
				<p class="text-3xl">{{ number_format($totalSales, 2) }} IDR</p>
			</div>
			<div class="rounded-lg bg-white p-6 shadow-lg">
				<h2 class="mb-2 text-xl font-semibold">Pesanan Aktif</h2>
				<p class="text-3xl">{{ $activeOrders }}</p>
			</div>
			<div class="rounded-lg bg-white p-6 shadow-lg">
				<h2 class="mb-2 text-xl font-semibold">Kepuasan Pelanggan</h2>
				<p class="text-3xl">{{ $customerSatisfaction }}%</p>
			</div>
		</div>

		<!-- Aktivitas Terbaru -->
		<div class="mb-8">
			<h2 class="mb-4 text-xl font-semibold">Aktivitas Terbaru</h2>
			<ul>
				@foreach ($recentActivities as $activity)
					<li class="mb-2 rounded-lg bg-white p-4 shadow-lg">
						Pesanan ID: {{ $activity->id }} - {{ $activity->status }} pada {{ $activity->created_at->format('d-m-Y H:i') }}
					</li>
				@endforeach
			</ul>
		</div>

		<!-- Notifikasi Penting -->
		<div>
			<h2 class="mb-4 text-xl font-semibold">Notifikasi Penting</h2>
			<ul>
				@foreach ($importantNotifications as $notification)
					<li class="mb-2 rounded-lg bg-white p-4 shadow-lg">
						Pengingat untuk: {{ $notification->customer->name }} - {{ $notification->message }} pada
						{{ $notification->reminder_date->format('d-m-Y') }}
					</li>
				@endforeach
			</ul>
		</div>
	</div>
</x-app-layout>
