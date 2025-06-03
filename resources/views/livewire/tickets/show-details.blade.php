<div>
    @if (!empty($ticketId))
        <flux:callout class="bg-gray-50 dark:bg-zinc-900 shadow-sm">
            <flux:callout.heading icon="ticket">
                <strong>Ticket ID:</strong> {{ $ticketId }}
            </flux:callout.heading>
            <flux:separator />
            <flux:callout.text>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-3">
                        <livewire:tickets.ticket-details :ticketId="$ticketId" :key="'ticket-details-' . $ticketId" lazy />
                    </div>
                    <div class="md:col-span-2">
                        <livewire:tickets.policy :ticketId="$ticketId" :key="'policy-' . $ticketId" lazy />
                    </div>
                </div>
            </flux:callout.text>
        </flux:callout>
    @endif
</div>
