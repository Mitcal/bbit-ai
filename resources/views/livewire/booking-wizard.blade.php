<div>
    @if (! $chatStarted)
        {{-- Step 1: Booking form --}}
        <flux:heading size="xl" level="1" class="mb-1">Book a Window Clean</flux:heading>
        <flux:text class="mb-6">Tell us what you need and our AI assistant will help you get booked in.</flux:text>
        <flux:separator variant="subtle" class="mb-6" />

        <div class="max-w-md">
            <form wire:submit="startChat">
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>Service required</flux:label>
                        <flux:select wire:model="service" placeholder="Select a service">
                            @foreach (\App\Enums\ServiceType::cases() as $type)
                                <flux:select.option value="{{ $type->value }}">{{ $type->value }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="service" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Preferred date</flux:label>
                        <flux:date-picker
                            wire:model="preferredDate"
                            min="today"
                            placeholder="Select a date"
                            start-day="1"
                        />
                        <flux:error name="preferredDate" />
                    </flux:field>

                    <flux:button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="startChat">Chat with our assistant</span>
                        <span wire:loading wire:target="startChat">Connecting…</span>
                    </flux:button>

                    <flux:text size="sm" class="text-center text-zinc-400 dark:text-zinc-500">
                        Powered by AI · Responses may take a few seconds
                    </flux:text>
                </div>
            </form>
        </div>
    @else
        {{-- Step 2: AI chat --}}
        <flux:heading size="xl" level="1" class="mb-1">Booking Assistant</flux:heading>
        <flux:text class="mb-6">
            Chatting about: <strong>{{ $service }}</strong> on <strong>{{ $preferredDate }}</strong>
        </flux:text>
        <flux:separator variant="subtle" class="mb-6" />

        <div class="max-w-2xl">
            @if ($bookingCreated)
                <flux:callout variant="success" icon="check-circle" class="mb-4">
                    <flux:callout.heading>Booking Confirmed!</flux:callout.heading>
                    <flux:callout.text>
                        Your booking reference is <strong>#{{ $bookingId }}</strong>.
                        You can find it on the <flux:link href="{{ route('home') }}">bookings calendar</flux:link>.
                    </flux:callout.text>
                </flux:callout>
            @endif

            @if ($errorMessage)
                <flux:callout variant="danger" icon="exclamation-circle" class="mb-4">
                    <flux:callout.text>{{ $errorMessage }}</flux:callout.text>
                </flux:callout>
            @endif

            {{-- Chat messages --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden mb-4">
                <div
                    class="p-4 space-y-3 max-h-96 overflow-y-auto bg-zinc-50 dark:bg-zinc-900"
                    x-data="{
                        init() {
                            this.$nextTick(() => this.$el.scrollTop = this.$el.scrollHeight);
                            new MutationObserver(() => this.$el.scrollTop = this.$el.scrollHeight)
                                .observe(this.$el, { childList: true, subtree: true, characterData: true });
                        }
                    }"
                >
                    @foreach ($messages as $message)
                        @if ($message['role'] === 'user')
                            <div class="flex justify-end">
                                <div class="bg-blue-500 text-white rounded-2xl rounded-tr-sm px-4 py-2 max-w-[80%] text-sm">
                                    {{ $message['content'] }}
                                </div>
                            </div>
                        @elseif ($message['role'] === 'assistant')
                            <div class="flex justify-start">
                                <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-600 rounded-2xl rounded-tl-sm px-4 py-2 max-w-[80%] text-sm prose prose-sm dark:prose-invert">
                                    {!! \Illuminate\Support\Str::markdown($message['content']) !!}
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($isLoading)
                        <div class="flex justify-start">
                            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-600 rounded-2xl rounded-tl-sm px-4 py-2 max-w-[80%] text-sm prose prose-sm dark:prose-invert">
                                <span wire:stream="streamingResponse"></span>
                                <span x-data x-show="$el.previousElementSibling.textContent.trim() === ''" class="flex items-center gap-1.5">
                                    <span class="size-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0ms"></span>
                                    <span class="size-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 150ms"></span>
                                    <span class="size-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 300ms"></span>
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                @if (! $bookingCreated)
                    <div class="border-t border-zinc-200 dark:border-zinc-700 p-3 bg-white dark:bg-zinc-800">
                        <form wire:submit="sendMessage" class="flex gap-2">
                            <flux:input
                                autocomplete="off"
                                wire:model="userInput"
                                placeholder="Type your message…"
                                class="flex-1"
                                wire:loading.attr="disabled"
                                wire:target="sendMessage,startChat"
                            />
                            <flux:button
                                type="submit"
                                variant="primary"
                                icon="paper-airplane"
                                wire:loading.attr="disabled"
                                wire:target="sendMessage,startChat"
                            />
                        </form>
                    </div>
                @endif
            </div>

            <flux:button :href="route('home')" variant="ghost" icon="arrow-left" size="sm">
                View all bookings
            </flux:button>
        </div>
    @endif
</div>
