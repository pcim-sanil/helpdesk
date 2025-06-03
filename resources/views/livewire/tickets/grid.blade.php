<div>
    <div class="p-4 bg-white dark:bg-zinc-900 shadow-sm rounded-md">
        <!-- Filters Section -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-3">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <flux:input kbd="⌘K" size="sm" icon="magnifying-glass" placeholder="Search..." wire:model.live.debounce.500ms="search"/>
                    <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400 rounded-sm">
                        Search
                    </label>
                </div>

                <!-- High Priority Filter -->
                <div class="relative w-40 min-w-[150px] hidden sm:block">
                    <flux:select size="sm" placeholder="Choose High Priority" wire:model.live="isHighPriority">
                        <flux:select.option value="">All</flux:select.option>
                        <flux:select.option value="1">Yes</flux:select.option>
                        <flux:select.option value="0">No</flux:select.option>
                    </flux:select>
                    <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400 rounded-sm">
                        High Priority
                    </label>
                </div>

                <!-- Urgent Filter -->
                <div class="relative w-40 min-w-[150px] hidden sm:block">
                    <flux:select size="sm" placeholder="Choose Urgent" wire:model.live="isUrgent">
                        <flux:select.option value="">All</flux:select.option>
                        <flux:select.option value="1">Yes</flux:select.option>
                        <flux:select.option value="0">No</flux:select.option>
                    </flux:select>
                    <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400 rounded-sm">
                        Urgent
                    </label>
                </div>

                <!-- Status Filter -->
                <div class="relative w-40 min-w-[150px] hidden sm:block">
                    <flux:select size="sm" placeholder="Choose Status" wire:model.live="status">
                        @foreach($this->getStatusOptions() as $value => $label)
                            <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400 rounded-sm">
                        Status
                    </label>
                </div>

                <!-- Enquiry Type Filter -->
                <div class="relative w-40 min-w-[150px] hidden sm:block">
                    <flux:select size="sm" placeholder="Choose Enquiry Type" wire:model.live="enquiryType">
                        @foreach($this->getEnquiryTypeOptions() as $value => $label)
                            <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400 rounded-sm">
                        Type
                    </label>
                </div>

                <!-- Date Range Filter -->
                <div class="relative w-40 min-w-[150px] hidden sm:block">
                    <flux:select size="sm" placeholder="Choose Date Range" wire:model.live="dateRange">
                        @foreach($this->getDateRangeOptions() as $value => $label)
                            <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <label class="absolute -top-2 left-2 -mt-px px-1 bg-white dark:bg-zinc-900 text-xs font-medium text-gray-500 dark:text-gray-400 rounded-sm">
                        Date Range
                    </label>
                </div>
            </div>
        </div>

        <!-- Table Section with Loading Overlay -->
        <div class="relative overflow-x-auto">
            <!-- Centered Flux Icon Loading Spinner -->
            <div wire:loading.delay wire:target="search, status, enquiryType, dateRange, sortBy, isHighPriority, isUrgent, perPage, nextPage, previousPage, gotoPage" class="absolute inset-0 flex items-center justify-center bg-white/60 dark:bg-zinc-900/60 z-10">
                <div class="h-full flex items-center justify-center">
                    <flux:icon.loading class="w-6 h-6 text-gray-500 dark:text-gray-300" />
                </div>
            </div>
            

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th wire:click="sortBy('tickets.ticket_id')" class="cursor-pointer p-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                            TicketID
                            @if($sortColumn === 'tickets.ticket_id')
                                <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('tickets.enquiry_type')" class="cursor-pointer p-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                            Type
                            @if($sortColumn === 'tickets.enquiry_type')
                                <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('company_info.company_name')" class="cursor-pointer p-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                            Company
                            @if($sortColumn === 'company_info.company_name')
                                <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('tickets.caller_info_mobile')" class="cursor-pointer p-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                            Phone
                            @if($sortColumn === 'tickets.caller_info_mobile')
                                <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('tickets.created_date')" class="cursor-pointer p-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                            Created
                            @if($sortColumn === 'tickets.created_date')
                                <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('users.name')" class="cursor-pointer p-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                            Operator
                            @if($sortColumn === 'users.name')
                                <span>@if($sortDirection === 'asc') ▲ @else ▼ @endif</span>
                            @endif
                        </th>
                        <th class="p-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                            View
                        </th>
                        <th class="p-4 text-left text-xs font-bold text-gray-600 dark:text-gray-400 tracking-wider">
                            Assigned
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($tickets as $ticket)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" wire:key="ticket-{{ $ticket->ticket_id }}">
                            <td class="p-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket->ticket_id }}</td>
                            <td class="p-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                {{ App\Features\Ticket\TicketService::getEnquiryTypeLabel($ticket->enquiry_type) }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket->company_name }}</td>
                            <td class="p-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket->caller_info_mobile }}</td>
                            <td class="p-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ date('d/m/Y H:i', strtotime($ticket->created_date)) }}
                            </td>
                            <td class="p-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $ticket->operator_name }}</td>
                            <td class="p-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                <flux:button size="sm" icon="eye" class="cursor-pointer" wire:click="showTicket({{ $ticket->ticket_id }})">View</flux:button>
                            </td>
                            <td class="p-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                @if($ticket->operator_id == 0)
                                    <flux:button size="sm" icon="user-plus">Assign</flux:button>
                                @else
                                    <flux:button size="sm" icon="user-minus" variant="danger">Un-assign</flux:button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Per Page Selector (always first) -->
            <div>
                <flux:select size="sm" placeholder="Per Page" wire:model.live="perPage">
                    <flux:select.option value="5">5</flux:select.option>
                    <flux:select.option value="10">10</flux:select.option>
                    <flux:select.option value="25">25</flux:select.option>
                    <flux:select.option value="50">50</flux:select.option>
                </flux:select>
            </div>
        
            <!-- Pagination Controls -->
            <div class="flex justify-end">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
    <!-- Show Ticket Modal -->
    <div class="mt-4">
        <livewire:tickets.show-details />
    </div>
</div>
