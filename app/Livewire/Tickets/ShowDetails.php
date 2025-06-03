<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Tickets\Events;
use Illuminate\Contracts\View\View;
/**
 * This component is used to wrap the all other components that are used to show the details of a ticket.
 * It is also responsible for loading the data for the ticket when the ticket id is passed to it.
 */
class ShowDetails extends Component
{
    public ?int $ticketId = null;

    #[On(Events::ShowTicket->value)]
    public function loadData(int $ticketId): void
    {
        $this->ticketId = $ticketId;
    }

    public function render(): View
    {
        return view('livewire.tickets.show-details');
    }
}
