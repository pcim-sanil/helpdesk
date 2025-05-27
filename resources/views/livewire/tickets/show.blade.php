<div class="relative mt-6 p-4 border rounded-md bg-gray-50 dark:bg-zinc-900 dark:border-zinc-700">
    <!-- Loading Animation -->
    <div wire:loading class="absolute inset-0 flex items-center justify-center bg-white/60 dark:bg-zinc-900/60 z-10">
        <div class="h-full flex items-center justify-center">
            <flux:icon.loading class="w-6 h-6 text-gray-500 dark:text-gray-300" />
        </div>
    </div>
    
    <!-- Content (hidden when loading) -->
    <div>
        @if ($ticket)
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                Details for #{{ $ticket->ticket_id }}
            </h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="font-medium">Caller</dt>
                    <dd class="text-gray-600 dark:text-gray-300">{{ $ticket->caller_info_mobile }}</dd>
                </div>
                <div>
                    <dt class="font-medium">Status</dt>
                    <dd class="text-gray-600 dark:text-gray-300">{{ $ticket->status }}</dd>
                </div>
                <!-- Add more fields as needed -->
            </dl>
        @else
            <p class="text-gray-500">Item not found.</p>
        @endif
    </div>
</div>
