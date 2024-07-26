<!-- Sidenav -->
<div :class="{ 'translate-x-0': open, '-translate-x-full': !open }"
	class="fixed inset-0 z-40 flex transform transition-transform duration-300 ease-in-out md:hidden" role="dialog"
	aria-modal="true">
	<div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" @click="open = false"></div>
	<div class="relative flex w-full max-w-xs flex-1 flex-col bg-white">
		<div class="absolute right-0 top-0 -mr-12 pt-2">
			<button @click="open = false"
				class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:bg-gray-600 focus:outline-none">
				<span class="sr-only">Close sidebar</span>
				<svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
				</svg>
			</button>
		</div>
		<div class="h-0 flex-1 overflow-y-auto pb-4 pt-5">
			<nav class="mt-5 space-y-1 px-2">
				<!-- Add your sidenav items here -->
				<a href="#"
					class="group flex items-center rounded-md px-2 py-2 text-base font-medium leading-6 text-gray-900 hover:bg-gray-50 hover:text-gray-900">
					Dashboard
				</a>
				<!-- More items... -->
			</nav>
		</div>
	</div>
	<div class="w-14 flex-shrink-0" aria-hidden="true"></div>
</div>
