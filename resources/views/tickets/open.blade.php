<x-layouts.app :title="__('Tickets')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:tickets.grid :status="$status" />
    </div>
</x-layouts.app>
