<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use App\Models\Helpdesk\TicketModel;

class Show extends Component
{
    public ?int $ticketId = null;
    public ?TicketModel $ticket = null;

    public function mount(?int $ticketId): void
    {
        $this->ticketId = $ticketId;

        if ($this->ticketId) {
            $this->loadTicket();
        }
    }

    public function loadTicket(): void
    {
        $this->ticket = TicketModel::find($this->ticketId);
    }

    public function render()
    {
        return view('livewire.tickets.show');
    }
}
