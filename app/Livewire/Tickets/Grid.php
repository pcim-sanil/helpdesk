<?php

namespace App\Livewire\Tickets;

use App\Services\TicketService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Grid extends Component
{
    use WithPagination;

    /**
     * The number of tickets to show per page
     */
    #[Url(history: true)]
    public int $perPage = 5;

    /**
     * The column to sort by
     *t
     */
    #[Url(history: true)]
    public string $sortColumn = 'tickets.ticket_id';

    /**
     * The direction to sort by
     */
    #[Url(history: true)]
    public string $sortDirection = 'desc';

    /**
     * The search query
     */
    #[Url(history: true)]
    public string $search = '';

    /**
     * The status of the tickets
     */
    #[Url(history: true)]
    public ?string $status = null;

    /**
     * The operator of the tickets
     */
    #[Url(history: true)]
    public ?string $dateRange = 'last_3_months';

    /**
     * The enquiry type filter
     */
    #[Url(history: true)]
    public ?string $enquiryType = null;

    /**
     * The urgent filter
     *
     * @var bool
     */
    #[Url(history: true)]
    public ?int $isUrgent = null;

    /**
     * The high priority filter
     *
     * @var bool
     */
    #[Url(history: true)]
    public ?int $isHighPriority = null;

    /**
     * Get the date range options
     */
    public function getDateRangeOptions(): array
    {
        return [
            'last_month' => 'Last Month',
            'last_3_months' => 'Last 3 Months',
            'last_6_months' => 'Last 6 Months',
            'last_12_months' => 'Last 12 Months',
            'all' => 'All Time',
        ];
    }

    /**
     * Reset pagination when date range changes
     */
    public function resetPagination(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when search changes
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Sort the tickets by a column
     *
     * @return void
     */
    public function sortBy(string $field)
    {
        $this->sortColumn = $field;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    /**
     * Get the status options
     */
    public function getStatusOptions(): array
    {
        $ticketStatuses = TicketService::getStatusOptions();

        return ['all' => 'All Status'] + $ticketStatuses;
    }

    /**
     * Get the enquiry type options
     */
    public function getEnquiryTypeOptions(): array
    {
        $enquiryTypes = TicketService::getEnquiryTypeOptions();

        return ['all' => 'All Types'] + $enquiryTypes;
    }

    /**
     * Show the ticket
     */
    public function showTicket(int $ticketId): void
    {
        $this->dispatch(Events::ShowTicket->value, $ticketId);
        $this->skipRender();
    }

    /**
     * Get the tickets
     */
    private function getTickets(): LengthAwarePaginator
    {
        $query = DB::connection('helpdesk')
            ->table('tickets')
            ->leftJoin('users', 'users.id', '=', 'tickets.operator_id')
            ->leftJoin('sms_services', 'sms_services.service_1300', '=', 'tickets.service_1300')
            ->leftJoin('company_info', 'company_info.id', '=', 'sms_services.company_info_id')
            ->select([
                'tickets.ticket_id',
                'tickets.service_1300',
                'tickets.caller_info_mobile',
                'tickets.enquiry_type',
                'tickets.status',
                'tickets.operator_id',
                'tickets.assigned_to',
                'tickets.created_date',
                'users.name as operator_name',
                'company_info.company_name as company_name',
            ]);

        // Status filter
        if ($this->status !== null && $this->status !== 'all') {
            $query->where('tickets.status', '=', $this->status);
        }

        // Enquiry Type filter
        if ($this->enquiryType !== null && $this->enquiryType !== 'all') {
            $query->where('tickets.enquiry_type', '=', $this->enquiryType);
        }

        // Date range filter
        if ($this->dateRange !== 'all') {
            $date = match ($this->dateRange) {
                'last_month' => now()->subMonth(),
                'last_3_months' => now()->subMonths(3),
                'last_6_months' => now()->subMonths(6),
                'last_12_months' => now()->subMonths(12),
                default => null
            };

            if ($date) {
                $query->where('tickets.created_date', '>=', $date->format('Y-m-d'));
            }
        }

        // High Priority filter
        if ($this->isHighPriority !== null && $this->isHighPriority !== 'all') {
            if ($this->isHighPriority === 1) {
                $query->whereIn('tickets.service_1300', function ($query) {
                    $query->select('service_1300')
                        ->from('high_priority_sms_services');
                });
            } else {
                $query->whereNotIn('tickets.service_1300', function ($query) {
                    $query->select('service_1300')
                        ->from('high_priority_sms_services');
                });
            }
        }

        // Urgent filter
        if ($this->isUrgent !== null && $this->isUrgent !== 'all') {
            $query->where('tickets.is_urgent', '=', $this->isUrgent);
        }

        // Search logic
        if (trim($this->search) !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('tickets.ticket_id', 'like', "{$search}%")
                        ->orWhere('tickets.caller_info_mobile', 'like', "{$search}%");
                } else {
                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('company_info.company_name', 'like', "%{$search}%");
                }
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        $tickets = $this->getTickets();

        Log::info(count($tickets));

        return view('livewire.tickets.grid', [
            'tickets' => $tickets,
        ]);
    }
}
