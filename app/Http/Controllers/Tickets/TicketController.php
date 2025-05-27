<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function open(): View
    {
        return view('tickets.open', [
            'status' => '0',
        ]);
    }

    public function closed(): View
    {
        return view('tickets.all', [
            'status' => '1',
        ]);
    }

    public function all(): View
    {
        return view('tickets.all', [
            'status' => null,
        ]);
    }
}