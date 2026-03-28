<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @fluxAppearance
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 font-instrument-sans antialiased">
        <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:brand href="{{ route('home') }}" name="Bright Windows" />
            <flux:spacer />
            <flux:navbar>
                <flux:navbar.item :href="route('home')" :current="request()->routeIs('home')" wire:navigate>All Bookings</flux:navbar.item>
                <flux:navbar.item :href="route('book')" :current="request()->routeIs('book')" wire:navigate>Book Now</flux:navbar.item>
            </flux:navbar>
        </flux:header>

        <flux:main container>
            {{ $slot }}
        </flux:main>

        @fluxScripts

        <footer
            class="[grid-area:footer] lg:[grid-area:sidebar] lg:w-64 border-t lg:border-t-0 lg:border-r border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900"
            x-data="{ open: false }"
        >
            {{-- Mobile: toggleable bottom bar --}}
            <div class="lg:hidden">
                <button
                    @click="open = !open"
                    class="flex w-full items-center justify-between px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400"
                >
                    <span class="flex items-center gap-2">
                        <flux:badge color="yellow" size="sm">AI Demo</flux:badge>
                        <span>About this app</span>
                    </span>
                    <flux:icon.chevron-down class="size-4 transition-transform duration-200" ::class="{ 'rotate-180': open }" />
                </button>

                <div x-show="open" x-collapse.duration.200ms>
                    <div class="px-4 pb-5 space-y-4">
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                            A demo showcasing AI-optimised bookings, built by <a href="https://bumblebeeitsolutions.com/" target="_blank" rel="noopener noreferrer" class="font-medium text-zinc-700 dark:text-zinc-300 underline underline-offset-2 hover:text-yellow-500 transition-colors">Bumblebee IT Solutions</a>.
                        </flux:text>
                        <div class="flex flex-col gap-2">
                            <flux:button href="https://bumblebeeitsolutions.com/#newsletter" target="_blank" rel="noopener noreferrer" variant="filled" icon="envelope" size="sm">Subscribe to newsletter</flux:button>
                            <flux:button href="https://www.facebook.com/bumblebeeituk" target="_blank" rel="noopener noreferrer" variant="ghost" size="sm">Follow us on Facebook</flux:button>
                        </div>
                        <div class="space-y-3 pt-2 border-t border-zinc-200 dark:border-zinc-700">
                            <flux:heading size="sm" level="2" class="pt-2">How it works</flux:heading>
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 size-7 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                    <flux:icon.calendar-days class="size-3.5 text-yellow-600 dark:text-yellow-400" />
                                </div>
                                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400"><strong class="text-zinc-700 dark:text-zinc-300">Pick a service & date</strong> — use the booking form.</flux:text>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 size-7 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                    <flux:icon.chat-bubble-left-right class="size-3.5 text-yellow-600 dark:text-yellow-400" />
                                </div>
                                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400"><strong class="text-zinc-700 dark:text-zinc-300">Chat with the AI</strong> — it checks availability and suggests alternatives.</flux:text>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 size-7 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                    <flux:icon.check-circle class="size-3.5 text-yellow-600 dark:text-yellow-400" />
                                </div>
                                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400"><strong class="text-zinc-700 dark:text-zinc-300">Confirm</strong> — your booking is instantly created.</flux:text>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Desktop: left sidebar --}}
            <div class="hidden lg:flex flex-col gap-5 p-5 sticky top-0">
                <flux:badge color="yellow">AI Demo Application</flux:badge>

                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                    A test application showcasing AI-optimised bookings.
                </flux:text>

                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                    Built by <a href="https://bumblebeeitsolutions.com/" target="_blank" rel="noopener noreferrer" class="font-medium text-zinc-700 dark:text-zinc-300 underline underline-offset-2 hover:text-yellow-500 transition-colors">Bumblebee IT Solutions</a> — a UK-based IT agency developing bespoke web applications.
                </flux:text>

                <div class="flex flex-col gap-2">
                    <flux:button href="https://bumblebeeitsolutions.com/#newsletter" target="_blank" rel="noopener noreferrer" variant="filled" icon="envelope" size="sm">Subscribe to newsletter</flux:button>
                    <flux:button href="https://www.facebook.com/bumblebeeituk" target="_blank" rel="noopener noreferrer" variant="ghost" size="sm">Follow us on Facebook</flux:button>
                </div>

                <flux:separator variant="subtle" />

                <div class="space-y-4">
                    <flux:heading size="sm" level="2">How it works</flux:heading>
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 size-7 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                            <flux:icon.calendar-days class="size-3.5 text-yellow-600 dark:text-yellow-400" />
                        </div>
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400"><strong class="text-zinc-700 dark:text-zinc-300">Pick a service & date</strong> — use the booking form.</flux:text>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 size-7 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                            <flux:icon.chat-bubble-left-right class="size-3.5 text-yellow-600 dark:text-yellow-400" />
                        </div>
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400"><strong class="text-zinc-700 dark:text-zinc-300">Chat with the AI</strong> — it checks availability and suggests alternatives.</flux:text>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 size-7 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                            <flux:icon.check-circle class="size-3.5 text-yellow-600 dark:text-yellow-400" />
                        </div>
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400"><strong class="text-zinc-700 dark:text-zinc-300">Confirm</strong> — your booking is instantly created.</flux:text>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
