<flux:callout>
    <flux:callout.heading icon="newspaper" icon:variant="outline">
        Policy Details
    </flux:callout.heading>
    <flux:separator />
    <flux:callout.text>
        @if ($refundPolicies->count() > 0)
            <ol class="list-decimal pl-5 space-y-2">
                @foreach ($refundPolicies as $policy)
                    <li class="text-gray-700">{{ $policy->content }}</li>
                @endforeach
            </ol>
        @endif
    </flux:callout.text>
</flux:callout>
