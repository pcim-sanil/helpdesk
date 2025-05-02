<x-layouts.app :title="__('Tickets')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:tickets.ticket-list :status="$status" />
    </div>
</x-layouts.app>
