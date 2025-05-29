<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Tickets\Events;

class ShowDetails extends Component
{
    public ?int $ticketId = null;

    #[On(Events::ShowTicket->value)]
    public function loadDetails(int $ticketId)
    {
        $this->ticketId = $ticketId;
    }

    public function render()
    {
        return view('livewire.tickets.show-details');
    }
}
