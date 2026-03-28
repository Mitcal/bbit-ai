<div>
    <flux:heading size="xl" level="1" class="mb-1">Upcoming Bookings</flux:heading>
    <flux:text class="mb-3 sm:mb-6">View all scheduled window cleaning appointments.</flux:text>
    <flux:separator variant="subtle" class="mb-3 sm:mb-6" />

    <div
        x-data="{}"
        x-init="initCalendar($el, '{{ route('api.bookings.events') }}')"
        class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 p-4"
    ></div>
</div>
