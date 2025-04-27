<div class="p-4 bg-white dark:bg-zinc-900 rounded-xl shadow-sm">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Tickets</h2>

    <div class="mb-4 flex items-center gap-2">
        <div class="relative flex-1">
            <input
                type="text"
                wire:model.live.debounce.250ms="search"
                placeholder="Search by Ticket ID, Phone, Operator, or Company"
                class="w-full pl-10 pr-10 py-2 rounded border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                <!-- Search icon SVG -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </span>
            @if($search)
                <button
                    wire:click="$set('search', '')"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                    aria-label="Clear search"
                    type="button"
                >
                    <!-- X icon SVG -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            @endif
        </div>
    </div>

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

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</div>