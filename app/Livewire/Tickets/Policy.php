<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use App\Models\Helpdesk\TicketModel;
use Illuminate\Support\Collection;

class Policy extends Component
{
    public ?int $ticketId;
    public Collection $refundPolicies;

    public function mount(int $ticketId)
    {
        $this->ticketId = $ticketId;


        // fetch ticket model and store in container .
        $ticketModel = TicketModel::query()
            ->with(['smsService.refundPolicy', 'smsService.companyInfo.refundPolicy'])
            ->where('ticket_id', $this->ticketId)
            ->first();

        $serviceLevelPolicy = $ticketModel->smsService->refundPolicy;
        $companyLevelPolicy = $ticketModel->smsService->companyInfo->refundPolicy;

        if($serviceLevelPolicy->count() > 0){
            $this->refundPolicies = $serviceLevelPolicy;
        }else{
            $this->refundPolicies = $companyLevelPolicy;
        }
    }

    public function placeholder()
    {
        return view('livewire.placeholders.content-holder');
    }

    public function render()
    {
        return view('livewire.tickets.policy');
    }
}
