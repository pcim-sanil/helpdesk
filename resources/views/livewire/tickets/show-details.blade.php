<div>
    @if(!empty($ticketId))
    <h1>Ticket Details: {{ $ticketId }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <livewire:tickets.service :ticketId="$ticketId" :key="'service-' . $ticketId" />
        <hr/>
        <livewire:tickets.policy :ticketId="$ticketId" :key="'policy-' . $ticketId" />
        <hr/>
        <livewire:tickets.customer :ticketId="$ticketId" :key="'customer-' . $ticketId" />
    </div>
    @endif
</div>