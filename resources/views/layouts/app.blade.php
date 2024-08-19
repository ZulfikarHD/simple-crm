<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

	<!-- Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-data="{ open: false }">
	<div class="flex min-h-screen bg-gray-100">

		@include('layouts.sidenav-small')

		@include('layouts.sidenav-dekstop')

		<div class="flex w-0 flex-1 flex-col overflow-hidden">
			<div class="pl-1 pt-1 sm:pl-3 sm:pt-3 md:hidden">
				<button @click="open = true"
					class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:bg-gray-100 focus:outline-none">
					<span class="sr-only">Open sidebar</span>
					<svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
					</svg>
				</button>
			</div>
			<main class="relative z-0 flex-1 overflow-y-auto focus:outline-none">
				<div class="py-6">
					<div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
						<!-- Page Heading -->
						@isset($header)
							<header class="bg-white shadow">
								<div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
									{{ $header }}
								</div>
							</header>
						@endisset

						<!-- Page Content -->
						<main>
							{{ $slot }}
						</main>
					</div>
				</div>
			</main>
		</div>
	</div>
    @stack('js')
    @stack('sweet-alert')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      lucide.createIcons();
    </script>
</body>

</html>
