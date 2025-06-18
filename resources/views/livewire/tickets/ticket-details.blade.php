<div class="space-y-8">

    <!-- Service Details Section -->
    <flux:callout>
        <flux:callout.heading icon="building-storefront" icon:variant="outline">
            Service Details
        </flux:callout.heading>
        <flux:separator />
        <flux:callout.text class="grid grid-cols-1 md:grid-cols-2 gap-6">

            @if($companyName)
                <flux:field>
                    <flux:label>Company Name</flux:label>
                    <flux:text>{{ $companyName }}</flux:text>
                </flux:field>
            @endif

            @if($service1300)
                <flux:field>
                    <flux:label>Service 1300</flux:label>
                    <flux:text>{{ $service1300 }}</flux:text>
                </flux:field>
            @endif

            <flux:field>
                <flux:label>Brand Name</flux:label>
                <flux:select wire:model.live="selectedSmsService" placeholder="Choose brand...">
                    @foreach($this->smsServices as $smsService)
                        <flux:select.option value="{{ $smsService->id }}">{{ $smsService->brand_name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="selectedSmsService" />
            </flux:field>

            <flux:field>
                <flux:label>Short Code</flux:label>
                <flux:select wire:model="selectedShortCode" placeholder="Choose short code..." :disabled="!$selectedSmsService">
                    @if($selectedShortCode === null)
                        <flux:select.option value="">Choose short code...</flux:select.option>
                    @endif
                    @foreach($this->smsServiceShortCodes as $code)
                        <flux:select.option value="{{ $code->short_code }}">{{ $code->short_code }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="selectedShortCode" />
            </flux:field>

            @if($welcomeText)
                <flux:field class="col-span-2">
                    <flux:label class="font-bold text-yellow-600 dark:text-yellow-400">Welcome Message</flux:label>
                    <flux:text class="text-lg font-medium mt-1 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
                        {{ $welcomeText }}
                    </flux:text>
                </flux:field>
            @endif

        </flux:callout.text>
    </flux:callout>

    <!-- Customer Information Section -->
    <flux:callout>
        <flux:callout.heading icon="identification" icon:variant="outline">
            Customer Information
        </flux:callout.heading>
        <flux:separator />
        <flux:callout.text class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <flux:field>
                <flux:label>Caller Name</flux:label>
                <flux:input wire:model="callerName" type="text" />
                <flux:error name="callerName" />
            </flux:field>

            <flux:field>
                <flux:label>Caller Email</flux:label>
                <flux:input wire:model="callerEmail" type="email" />
                <flux:error name="callerEmail" />
            </flux:field>

            <flux:field>
                <flux:label>Caller Mobile</flux:label>
                <flux:input wire:model="callerMobile" type="text" />
                <flux:error name="callerMobile" />
            </flux:field>

            <flux:field>
                <flux:label>Callback Number</flux:label>
                <flux:input wire:model="callbackNumber" type="text" />
                <flux:error name="callbackNumber" />
            </flux:field>

            <flux:field>
                <flux:label>Reason for Contact</flux:label>
                <flux:select wire:model="reasonForContact" placeholder="Choose reason for contact...">
                    <flux:select.option value="Technical Support">Technical Support</flux:select.option>
                    <flux:select.option value="Billing Inquiry">Billing Inquiry</flux:select.option>
                    <flux:select.option value="Service Request">Service Request</flux:select.option>
                    <flux:select.option value="Complaint">Complaint</flux:select.option>
                    <flux:select.option value="General Inquiry">General Inquiry</flux:select.option>
                </flux:select>
                <flux:error name="reasonForContact" />
            </flux:field>

            <flux:field>
                <flux:label>Inquiry Category</flux:label>
                <flux:input wire:model="category" type="text" readonly />
            </flux:field>

            <flux:field>
                <flux:label>Carrier</flux:label>
                <flux:select wire:model="carrier" placeholder="Select Carrier">
                    <flux:select.option value="Telstra">Telstra</flux:select.option>
                    <flux:select.option value="Optus">Optus</flux:select.option>
                    <flux:select.option value="Vodafone">Vodafone</flux:select.option>
                    <flux:select.option value="TPG">TPG</flux:select.option>
                    <flux:select.option value="Vocus">Vocus</flux:select.option>
                    <flux:select.option value="Other">Other</flux:select.option>
                </flux:select>
                <flux:error name="carrier" />
            </flux:field>

        </flux:callout.text>
    </flux:callout>

    <!-- Notes Section -->
    <flux:callout>
        <flux:callout.heading icon="pencil-square" icon:variant="outline">
            Notes
        </flux:callout.heading>
        <flux:separator />
        <flux:callout.text class="space-y-4">
            
            <!-- Add New Note -->
            <div>
                <flux:field>
                    <flux:label>Add New Note</flux:label>
                    <flux:textarea wire:model="newNote" rows="3" placeholder="Enter your note here..." />
                    <flux:error name="newNote" />
                </flux:field>
                <div class="mt-2 flex items-center gap-2">
                    <flux:button wire:click="addNote" variant="primary" size="sm" :disabled="empty(trim($newNote))">
                        Add Note
                    </flux:button>
                    @if (session()->has('note-added'))
                        <flux:text class="text-sm text-green-600 dark:text-green-400">
                            {{ session('note-added') }}
                        </flux:text>
                    @endif
                </div>
            </div>

            <!-- Notes History -->
            @if($this->notesHistory->isNotEmpty())
                <div class="space-y-3">
                    <flux:label class="text-sm font-medium">Notes History</flux:label>
                    @foreach($this->notesHistory as $note)
                        <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4 border border-gray-200 dark:border-zinc-700 }}">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $note->created_date ? \Carbon\Carbon::parse($note->created_date)->format('M d, Y g:i A') : '' }}
                                </span>
                                <div class="flex items-center gap-2">
                                    @if($note->createdBy)
                                        <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                            {{ $note->createdBy->name ?? 'Unknown' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $note->note }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

        </flux:callout.text>
    </flux:callout>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4">
        <flux:button wire:click="saveTicket" variant="primary">
            Save Changes
        </flux:button>
    </div>
</div>
