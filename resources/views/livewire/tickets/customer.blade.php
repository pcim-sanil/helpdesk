<div x-data="{ loaded: false }" x-init="
    if (!loaded && {{ $ticketId ? 'true' : 'false' }}) {
        loaded = true;
        setTimeout(() => {
            console.log('Loading customer data');
            $wire.loadCustomerData();
        }, 300);
    }
" class="relative">

        <!-- Loading State -->
        <div wire:loading wire:target="loadCustomerData" class="absolute inset-0 flex items-center justify-center bg-white/60 dark:bg-zinc-900/60 z-10">
            <div class="flex items-center space-x-2">
                <flux:icon.loading class="w-5 h-5 text-gray-500 dark:text-gray-300" />
                <span class="text-sm text-gray-500 dark:text-gray-300">Loading customer details...</span>
            </div>
        </div>
    <div class="p-4 bg-white dark:bg-zinc-900 shadow-sm rounded-md">
        <!-- Content -->
            <div wire:loading.remove wire:target="loadCustomerData" class="space-y-2">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Customer Details</h2>
                </div>
                <p class="text-sm"><span class="font-medium">Ticket ID:</span> {{ $ticketId }}</p>
                @if(!empty($customerData))
                    <p class="text-sm"><span class="font-medium">Customer Name:</span> {{ $customerData['customer_name'] ?? 'N/A' }}</p>
                    <p class="text-sm"><span class="font-medium">Email:</span> {{ $customerData['email'] ?? 'N/A' }}</p>
                    <p class="text-sm"><span class="font-medium">Phone:</span> {{ $customerData['phone'] ?? 'N/A' }}</p>
                    <p class="text-sm"><span class="font-medium">Address:</span> {{ $customerData['address'] ?? 'N/A' }}</p>
                @endif
            </div>
    </div>
</div>
