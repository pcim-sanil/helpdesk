<div class="relative">
    <!-- Ticket Header with Key Info -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-zinc-700 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <flux:badge size="lg" :color="$ticketData['status'] === 'Open' ? 'green' : ($ticketData['status'] === 'Closed' ? 'red' : 'yellow')">
                        {{ $ticketData['status'] }}
                    </flux:badge>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $ticketData['company_name'] }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ticketData['brand_name'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ticketData['created_date'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Created</p>
                </div>
            </div>
        </div>
        
        <!-- Quick Info Cards -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Caller Info Card -->
                <div class="bg-blue-50 dark:bg-blue-950/30 rounded-lg p-4 border border-blue-100 dark:border-blue-900/50">
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2 flex items-center">
                        <flux:icon name="user" class="w-4 h-4 mr-2" />
                        Caller Information
                    </h3>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Name:</span> {{ $ticketData['caller_name'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Mobile:</span> {{ $ticketData['caller_mobile'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Email:</span> {{ $ticketData['caller_email'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Callback:</span> {{ $ticketData['callback_number'] }}</p>
                    </div>
                </div>

                <!-- Service Info Card -->
                <div class="bg-green-50 dark:bg-green-950/30 rounded-lg p-4 border border-green-100 dark:border-green-900/50">
                    <h3 class="text-sm font-medium text-green-900 dark:text-green-100 mb-2 flex items-center">
                        <flux:icon name="phone" class="w-4 h-4 mr-2" />
                        Service Details
                    </h3>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Service 1300:</span> {{ $ticketData['service_1300'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Short Code:</span> {{ $ticketData['short_code'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Marketing No:</span> {{ $ticketData['marketing_no'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">End User No:</span> {{ $ticketData['enduser_marketing_no'] }}</p>
                    </div>
                </div>

                <!-- Operator Info Card -->
                <div class="bg-purple-50 dark:bg-purple-950/30 rounded-lg p-4 border border-purple-100 dark:border-purple-900/50">
                    <h3 class="text-sm font-medium text-purple-900 dark:text-purple-100 mb-2 flex items-center">
                        <flux:icon name="user-circle" class="w-4 h-4 mr-2" />
                        Operator Details
                    </h3>
                    <div class="space-y-1">
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Operator:</span> {{ $ticketData['username'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Type:</span> {{ $ticketData['operator_type'] }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Subcompany:</span> {{ $ticketData['subcompany'] }}</p>
                        @if($ticketData['reassigned_from'])
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Reassigned From:</span> {{ $ticketData['reassigned_from'] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ticket Classification -->
            <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4 mb-6 border border-gray-100 dark:border-zinc-700">
                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3 flex items-center">
                    <flux:icon name="tag" class="w-4 h-4 mr-2" />
                    Ticket Classification
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Enquiry Type</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ticketData['enquiry_type'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Category</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ticketData['category'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Reason for Contact</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ticketData['reason_for_contact'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Welcome Text</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ticketData['welcome_text'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            @if($ticketData['summary'])
            <div class="bg-yellow-50 dark:bg-yellow-950/30 rounded-lg p-4 mb-4 border border-yellow-100 dark:border-yellow-900/50">
                <h3 class="text-sm font-medium text-yellow-900 dark:text-yellow-100 mb-2 flex items-center">
                    <flux:icon name="document-text" class="w-4 h-4 mr-2" />
                    Summary
                </h3>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $ticketData['summary'] }}</p>
            </div>
            @endif

            <!-- Messages and Notes -->
            <div class="space-y-4">
                @if($ticketData['message'])
                <div class="border border-gray-200 dark:border-zinc-700 rounded-lg p-4 bg-white dark:bg-zinc-900">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                        <flux:icon name="microphone" class="w-4 h-4 mr-2" />
                        Recorded Message
                    </h3>
                    <div class="bg-gray-50 dark:bg-zinc-800 rounded p-3 border border-gray-100 dark:border-zinc-700">
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $ticketData['message'] }}</p>
                    </div>
                </div>
                @endif

                @if($ticketData['notes'])
                <div class="border border-gray-200 dark:border-zinc-700 rounded-lg p-4 bg-white dark:bg-zinc-900">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                        <flux:icon name="pencil" class="w-4 h-4 mr-2" />
                        Notes
                    </h3>
                    <div class="bg-gray-50 dark:bg-zinc-800 rounded p-3 border border-gray-100 dark:border-zinc-700">
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $ticketData['notes'] }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Additional Info -->
            @if($ticketData['stop'])
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <div class="flex items-center space-x-2">
                    <flux:icon name="stop" class="w-4 h-4 text-red-500 dark:text-red-400" />
                    <span class="text-sm font-medium text-red-700 dark:text-red-400">Stop: {{ $ticketData['stop'] }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
