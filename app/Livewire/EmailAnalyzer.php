<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class EmailAnalyzer extends Component
{
    public $emailContent = '';
    public $analysisResult = null;

    public function analyzeEmail()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])->retry(2, 2000)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o', // Free-tier compatible
                'temperature' => 0,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an AI that analyzes emails. Your job is to detect the user\'s intent (refund, unsubscribe, or other), and the language of the email in 2-letter ISO code. Output only a JSON object like: {"language":"en","intent":["refund","unsubscribe"]}.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->emailContent,
                    ],
                ],
            ]);

            if ($response->status() === 429) {
                $this->analysisResult = ['error' => 'Free-tier rate limit reached. Please try again later.'];
                return;
            }

            if (!$response->successful()) {
                $this->analysisResult = ['error' => 'API request failed with status: ' . $response->status()];
                return;
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? null;

            if ($content) {
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->analysisResult = $decoded;
                } else {
                    $this->analysisResult = ['error' => 'Invalid JSON format in response.'];
                }
            } else {
                $this->analysisResult = ['error' => 'No content returned from API.'];
            }
        } catch (\Exception $e) {
            $this->analysisResult = ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function render()
    {
        return view('livewire.email-analyzer')->layout('components.layouts.auth.simple');
    }
}
