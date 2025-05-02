<div class="p-4 bg-white dark:bg-zinc-900 shadow-sm">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Tickets</h2>

    <!-- Filters Section -->
    <div class="mb-6">
        <!-- Single Row Filters -->
        <div class="flex gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Search by Ticket ID, Phone, Operator, or Company"
                    class="w-full pl-10 pr-10 py-2.5 border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </span>
                @if($search)
                    <button
                        wire:click="$set('search', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
                <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400">
                    Search
                </label>
            </div>

            <!-- Status Filter -->
            <div class="relative w-40">
                <select
                    wire:model.live="status"
                    class="appearance-none w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-200 py-2.5 pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    @foreach($this->getStatusOptions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400">
                    Status
                </label>
            </div>

            <!-- Enquiry Type Filter -->
            <div class="relative w-40">
                <select
                    wire:model.live="enquiryType"
                    class="appearance-none w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-200 py-2.5 pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    @foreach($this->getEnquiryTypeOptions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400">
                    Type
                </label>
            </div>

            <!-- Date Range Filter -->
            <div class="relative w-40">
                <select
                    wire:model.live="dateRange"
                    class="appearance-none w-full border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-700 dark:text-gray-200 py-2.5 pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    @foreach($this->getDateRangeOptions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400">
                    Date Range
                </label>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-zinc-800">
                <tr>
                    <th wire:click="sortBy('tickets.ticket_id')" class="cursor-pointer px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                        TicketID
                        @if($sortColumn === 'tickets.ticket_id')
                            <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('tickets.caller_info_mobile')" class="cursor-pointer px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                        Phone
                        @if($sortColumn === 'tickets.caller_info_mobile')
                            <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('company_info.company_name')" class="cursor-pointer px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                        Company
                        @if($sortColumn === 'company_info.company_name')
                            <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('users.name')" class="cursor-pointer px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                        Operator
                        @if($sortColumn === 'users.name')
                            <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('tickets.enquiry_type')" class="cursor-pointer px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                        Type
                        @if($sortColumn === 'tickets.enquiry_type')
                            <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('tickets.created_date')" class="cursor-pointer px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                        Created
                        @if($sortColumn === 'tickets.created_date')
                            <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                        @endif
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($tickets as $ticket)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket->ticket_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket->caller_info_mobile }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket->company_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket->operator_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                        {{ App\Services\TicketService::getEnquiryTypeLabel($ticket->enquiry_type) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $ticket->created_date }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                        <a href="#" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</div>