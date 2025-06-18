<flux:callout>
    <flux:callout.heading icon="newspaper" icon:variant="outline">
        Policy Details
    </flux:callout.heading>
    <flux:separator />
    <flux:callout.text>
        @if ($refundPolicies->count() > 0)
            <style>
                .policy-list li::marker {
                    font-weight: bold;
                }
            </style>
            <ol class="list-decimal pl-5 space-y-2 policy-list">
                @foreach ($refundPolicies as $policy)
                    <li class="text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 px-2 py-1 rounded transition-colors duration-200">{{ $policy->content }}</li>
                @endforeach
            </ol>
        @endif
    </flux:callout.text>
</flux:callout>
