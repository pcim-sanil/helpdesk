<div x-data="{ loaded: false }" x-init="
    if (!loaded && {{ $ticketId ? 'true' : 'false' }}) {
        loaded = true;
        setTimeout(() => {
            $wire.loadServiceData();
        }, 10);
    }
">
    <div class="p-4 bg-white dark:bg-zinc-900 shadow-sm rounded-md relative">
        <!-- Loading State -->
        @if($isLoading)
            <div class="absolute inset-0 flex items-center justify-center bg-white/60 dark:bg-zinc-900/60 z-10">
                <div class="flex items-center space-x-2">
                    <flux:icon.loading class="w-5 h-5 text-gray-500 dark:text-gray-300" />
                    <span class="text-sm text-gray-500 dark:text-gray-300">Loading service details...</span>
                </div>
            </div>
        @endif

        <!-- Content -->
        <div class="@if($isLoading) opacity-50 @endif">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Service Details</h2>
                @if(!$isLoading && empty($serviceData) && $ticketId)
                    <flux:button size="sm" wire:click="loadServiceData">Load Data</flux:button>
                @endif
            </div>
            
            @if($ticketId && !$isLoading)
                <div class="space-y-2">
                    <p class="text-sm"><span class="font-medium">Ticket ID:</span> {{ $ticketId }}</p>
                    @if(!empty($serviceData))
                        <p class="text-sm"><span class="font-medium">Service Name:</span> {{ $serviceData['service_name'] ?? 'N/A' }}</p>
                        <p class="text-sm"><span class="font-medium">Service Type:</span> {{ $serviceData['service_type'] ?? 'N/A' }}</p>
                        <p class="text-sm"><span class="font-medium">Status:</span> {{ $serviceData['status'] ?? 'N/A' }}</p>
                    @endif
                </div>
            @elseif(!$isLoading)
                <p class="text-sm text-gray-500">No ticket selected</p>
            @endif
        </div>
    </div>
</div>