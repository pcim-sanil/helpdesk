<?php

namespace App\Livewire\Tickets;

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Helpdesk\SmsServicesModel;
use App\Models\Helpdesk\SmsServiceShortCodeModel;
use App\Models\Helpdesk\TicketModel;
use App\Models\Helpdesk\NotesModel;
use App\Models\Helpdesk\CallerInfoModel;
use Illuminate\Support\Facades\Auth;

class TicketDetails extends Component
{
    public int $ticketId;
    
    // Service Details
    public ?string $companyName = null;
    public ?string $service1300 = null;
    public ?string $welcomeText = null;
    public ?int $companyInfoId = null;
    
    // Customer Information - Ticket fields
    public ?string $callerMobile = null;
    public ?string $callerEmail = null;
    public ?string $callbackNumber = null;
    public ?string $enquiryType = null;
    public ?string $category = null;
    public ?string $reasonForContact = null;
    public ?string $carrier = null;
    public ?string $notes = null;
    
    // Customer Information - CallerInfo fields
    public ?string $callerName = null;
    
    // Notes
    public string $newNote = '';
    
    // Dropdown selections
    public ?int $selectedSmsService = null;
    public ?string $selectedShortCode = null;
    
    // Internal models (not exposed to view)
    private ?TicketModel $ticket = null;
    private ?CallerInfoModel $callerInfo = null;

    public function mount(int $ticketId): void
    {
        $this->ticketId = $ticketId;
        $this->loadTicketData();
    }

    public function updatedSelectedSmsService(): void
    {
        $this->selectedShortCode = null;
    }

    #[Computed]
    public function smsServices()
    {
        if (!$this->companyInfoId) {
            return collect();
        }

        return SmsServicesModel::where('company_info_id', $this->companyInfoId)
            ->select('id', 'brand_name')
            ->orderBy('brand_name')
            ->get();
    }

    #[Computed]
    public function smsServiceShortCodes()
    {
        if (!$this->selectedSmsService) {
            return collect();
        }

        return SmsServiceShortCodeModel::where('sms_service_id', $this->selectedSmsService)
            ->select('id', 'short_code')
            ->orderBy('short_code')
            ->get();
    }

    #[Computed]
    public function notesHistory()
    {
        $notes = collect();
        
        // Add ticket notes as first note if it exists
        if ($this->ticket && !empty($this->ticket->notes)) {
            $ticketNote = (object) [
                'note' => $this->ticket->notes,
                'created_date' => $this->ticket->created_date,
                'createdBy' => $this->ticket->operator,
            ];
            $notes->push($ticketNote);
        }
        
        // Add individual notes from NotesModel
        $individualNotes = $this->ticket?->notes()?->orderBy('created_date', 'desc')->get() ?? collect();
        
        return $notes->concat($individualNotes);
    }

    public function saveTicket(): void
    {
        if ($this->ticket) {
            // Update ticket fields
            $this->ticket->caller_info_mobile = $this->callerMobile;
            $this->ticket->caller_email = $this->callerEmail;
            $this->ticket->callback_number = $this->callbackNumber;
            $this->ticket->reason_for_contact = $this->reasonForContact;
            $this->ticket->carrier = $this->carrier;
            $this->ticket->notes = $this->notes;
            $this->ticket->save();
        }
        
        if ($this->callerInfo) {
            // Update caller info fields
            $this->callerInfo->enduser_name = $this->callerName;
            $this->callerInfo->save();
        }
        
        session()->flash('message', 'Ticket updated successfully!');
    }

    public function addNote(): void
    {
        if (!$this->ticket || empty(trim($this->newNote))) {
            return;
        }

        NotesModel::create([
            'ticket_id' => $this->ticket->ticket_id,
            'note' => trim($this->newNote),
            'created_by' => Auth::user()->helpdesk_user_id ?? 0,
        ]);

        // Clear the new note input
        $this->newNote = '';
        
        // Refresh the computed property by clearing the cache
        unset($this->computedPropertyCache['notesHistory']);
        
        session()->flash('note-added', 'Note added successfully!');
    }

    public function placeholder()
    {
        return view('livewire.placeholders.content-holder');
    }

    public function render()
    {
        return view('livewire.tickets.ticket-details');
    }

    private function loadTicketData(): void
    {
        $this->ticket = TicketModel::query()
            ->where('ticket_id', $this->ticketId)
            ->with([
                'notes' => function ($query) {
                    $query->orderBy('created_date', 'desc');
                },
                'smsService.companyInfo',
                'smsServiceShortCode',
                'operator'
            ])
            ->first();

        if (!$this->ticket) {
            return;
        }

        // Load service details
        $this->companyName = $this->ticket->smsService?->companyInfo?->company_name;
        $this->service1300 = $this->ticket->service_1300 ? explode('_', $this->ticket->service_1300)[0] : null;
        $this->welcomeText = $this->ticket->smsService?->welcome_text;
        $this->companyInfoId = $this->ticket->smsService?->companyInfo?->id;

        // Load ticket fields
        $this->callerMobile = $this->ticket->caller_info_mobile;
        $this->callerEmail = $this->ticket->caller_email;
        $this->callbackNumber = $this->ticket->callback_number;
        $this->enquiryType = $this->ticket->enquiry_type;
        $this->category = $this->ticket->category;
        $this->reasonForContact = $this->ticket->reason_for_contact;
        $this->carrier = $this->ticket->carrier;
        $this->notes = $this->ticket->notes;

        // Load caller info based on mobile number
        if ($this->ticket->caller_info_mobile) {
            $this->callerInfo = CallerInfoModel::where('enduser_mobile', $this->ticket->caller_info_mobile)
                ->orderBy('updated_date', 'desc')
                ->first();
            
            if ($this->callerInfo) {
                $this->callerName = $this->callerInfo->enduser_name;
            }
        }

        // Set selected values for dropdowns
        $this->selectedSmsService = $this->ticket->smsService?->id;
        $this->selectedShortCode = $this->ticket->smsServiceShortCode?->short_code;
    }
}
