<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class DataGrid extends Component
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
            ])
            ->where('tickets.status', '=', '0');

        // Search logic
        if (trim($this->search) !== '') {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('tickets.ticket_id', 'like', "{$search}%")
                      ->orWhere('tickets.caller_info_mobile', 'like', "{$search}%");
                } else {
                    $q->where('users.name', 'like', "{$search}%")
                      ->orWhere('company_info.company_name', 'like', "{$search}%");
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

        return view('livewire.tickets.data-grid', [
            'tickets' => $tickets,
        ]);
    }

}
