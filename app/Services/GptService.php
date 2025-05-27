<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GptService
{
    protected string $baseUri;

    protected string $apiKey;

    protected int $timeout;

    protected PendingRequest $http;

    public function __construct()
    {
        $this->baseUri = config('services.openai.base_uri', 'https://api.openai.com/v1');
        $this->apiKey = config('services.openai.key');
        $this->timeout = config('services.openai.timeout', 300);

        // Pre-configure the HTTP client
        $this->http = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
        ])
            ->timeout($this->timeout)
            ->retry(2, 100);
    }

    /**
     * Low-level call to any OpenAI endpoint under /v1.
     *
     * @param  string  $endpoint  e.g. 'chat/completions' or 'completions'
     * @param  array  $params  Any valid parameters for that endpoint
     * @return array|string Decoded JSON, or raw SSE-style string if streaming
     */
    public function call(string $endpoint, array $params = [])
    {
        $response = $this->http->post("{$this->baseUri}/{$endpoint}", $params);

        // Throw on client/server errors
        $response->throw();

        // If client asked for streaming, return raw body
        if (! empty($params['stream']) && $params['stream'] === true) {
            return $response->body();
        }

        // Otherwise decode as JSON
        return $response->json();
    }

    /**
     * Shortcut to the Chat Completions endpoint.
     *
     * @param  array  $params  Any valid Chat Completions parameters:
     *                         - model              (string)   e.g. "gpt-4o-mini"
     *                         - messages           (array)    [{"role":"user","content":"Hi"}...]
     *                         - temperature        (float)
     *                         - top_p              (float)
     *                         - n                  (int)
     *                         - stream             (bool)
     *                         - stop               (string|array)
     *                         - max_tokens         (int)
     *                         - presence_penalty   (float)
     *                         - frequency_penalty  (float)
     *                         - logit_bias         (array)
     *                         - user               (string)
     *                         - functions          (array)
     *                         - function_call      (string|array)
     *                         // …and any future params the API supports
     * @return array|string Decoded JSON, or raw SSE-style string if streaming
     */
    public function chat(array $params)
    {
        return $this->call('chat/completions', $params);
    }

    /**
     * Shortcut to the Text Completions endpoint.
     */
    public function complete(array $params)
    {
        return $this->call('completions', $params);
    }

    /**
     * Shortcut to the Embeddings endpoint.
     */
    public function embed(array $params)
    {
        return $this->call('embeddings', $params);
    }

    /**
     * List available models.
     */
    public function listModels(): array
    {
        return $this->call('models');
    }
}
