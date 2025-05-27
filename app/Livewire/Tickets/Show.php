<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use App\Models\Helpdesk\TicketModel;
use Livewire\Attributes\On;

class Show extends Component
{
    public ?TicketModel $ticket = null;

    #[On(Events::ShowTicket->value)] 
    public function showTicket(int $ticketId): void
    {
        $this->ticket = TicketModel::find($ticketId);
    }

    public function render()
    {
        return view('livewire.tickets.show');
    }
}
