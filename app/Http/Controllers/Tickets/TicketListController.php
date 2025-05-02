<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TicketListController extends Controller
{
    public function open(): View
    {
        return view('tickets.ticket-list', [
            'status' => '0', // Assuming '0' is the status for open tickets
        ]);
    }

    public function closed(): View
    {
        return view('tickets.ticket-list', [
            'status' => '1', // Assuming '1' is the status for closed tickets
        ]);
    }

    public function all(): View
    {
        return view('tickets.ticket-list', [
            'status' => null, // No status filter for all tickets
        ]);
    }
}
