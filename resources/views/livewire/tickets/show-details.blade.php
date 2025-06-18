<div>
    @if (!empty($ticketId))
        <flux:callout class="bg-gray-50 dark:bg-zinc-900 shadow-sm">
            <flux:callout.heading icon="ticket">
                <strong>Ticket ID:</strong> {{ $ticketId }}
            </flux:callout.heading>
            <flux:separator />
            <flux:callout.text>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <livewire:tickets.ticket-details :ticketId="$ticketId" :key="'ticket-details-' . $ticketId" lazy />
                    </div>
                    <div class="lg:col-span-1">
                        <livewire:tickets.policy :ticketId="$ticketId" :key="'policy-' . $ticketId" lazy />
                    </div>
                </div>
            </flux:callout.text>
        </flux:callout>
    @endif
</div>
