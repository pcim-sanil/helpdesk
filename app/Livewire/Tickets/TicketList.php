<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\TicketService;

class TicketList extends Component
{
    use WithPagination;

    /**
     * The number of tickets to show per page
     *
     * @var int
     */
    public int $perPage = 5;

    /**
     * The column to sort by
     *
     * @var string
     */
    public string $sortColumn = 'tickets.ticket_id';

    /**
     * The direction to sort by
     *
     * @var string
     */
    public string $sortDirection = 'desc';

    /**
     * The search query
     *
     * @var string
     */
    public string $search = '';

    /**
     * The status of the tickets
     *
     * @var string
     */
    public ?string $status = null;

    /**
     * The operator of the tickets
     *
     * @var string
     */
    public ?string $dateRange = 'last_3_months';

    /**
     * The enquiry type filter
     *
     * @var string|null
     */
    public ?string $enquiryType = null;

    /**
     * Get the date range options
     *
     * @return array
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
     *
     * @return void
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
     * @param string $field
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
     *
     * @return array
     */
    public function getStatusOptions(): array
    {
        $ticketStatuses = TicketService::getStatusOptions();

        return ['all' => 'All Status'] + $ticketStatuses;
    }

    /**
     * Get the enquiry type options
     *
     * @return array
     */
    public function getEnquiryTypeOptions(): array
    {
        $enquiryTypes = TicketService::getEnquiryTypeOptions();

        return ['all' => 'All Types'] + $enquiryTypes;
    }

    /**
     * Get the tickets
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
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
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        $tickets = $this->getTickets();

        return view('livewire.tickets.ticket-list', [
            'tickets' => $tickets,
        ]);
    }
}
