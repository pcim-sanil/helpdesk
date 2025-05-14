<div class="max-w-2xl mx-auto p-6">
    <textarea 
        wire:model.defer="emailContent" 
        placeholder="Paste your email here..." 
        class="w-full h-64 p-4 border border-gray-300 rounded mb-4 resize-none text-sm font-mono"
    ></textarea>

    <button 
        wire:click.debounce.500ms="analyzeEmail" 
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow"
        wire:loading.attr="disabled"
    >
        <span wire:loading.remove>Detect Intent</span>
        <span wire:loading>...</span>
    </button>

    @if($analysisResult)
        <div class="mt-6 p-4 border border-gray-200 bg-gray-50 rounded text-sm">
            @if(isset($analysisResult['error']))
                <div class="text-red-600 font-medium">Error: {{ $analysisResult['error'] }}</div>
            @else
                <div><strong>Language:</strong> {{ $analysisResult['language'] }}</div>
                <div><strong>Intent(s):</strong> {{ implode(', ', $analysisResult['intent']) }}</div>
            @endif
        </div>
    @endif
</div>
