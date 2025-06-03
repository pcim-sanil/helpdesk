<flux:callout>
    <flux:callout.heading icon="user" icon:variant="outline">
        Customer Details
    </flux:callout.heading>
    <flux:separator />
    <flux:callout.text>
        @if (!empty($customerData))
            <flux:fieldset>
                <div class="space-y-6">
                    <flux:input label="Street address line 1" placeholder="123 Main St" class="max-w-sm" />
                    <flux:input label="Street address line 2" placeholder="Apartment, studio, or floor"
                        class="max-w-sm" />

                    <div class="grid grid-cols-2 gap-x-4 gap-y-6">
                        <flux:input label="City" placeholder="San Francisco" />
                        <flux:input label="State / Province" placeholder="CA" />
                        <flux:input label="Postal / Zip code" placeholder="12345" />
                        <flux:select label="Country">
                            <option selected>United States</option>
                            <!-- ... -->
                        </flux:select>
                    </div>
                </div>
            </flux:fieldset>
        @endif
    </flux:callout.text>
</flux:callout>
