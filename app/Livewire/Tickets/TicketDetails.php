<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use App\Features\Ticket\TicketService;

class TicketDetails extends Component
{
    public int $ticketId;
    public array $ticketData;

    public function mount(int $ticketId)
    {
        $this->ticketId = $ticketId;
        $this->ticketData = TicketService::getTicketDetails($ticketId)->toArray();
    }

    public function placeholder()
    {
        return view('livewire.placeholders.content-holder');
    }

    public function render()
    {
        return view('livewire.tickets.ticket-details');
    }
}
