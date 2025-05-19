<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GptService;

class EmailAnalyzer extends Component
{
    public $emailContent = '';
    public $analysisResult = null;

    public function analyzeEmail()
    {
        $gptService = new GptService();
        try {
            $params = [
                'model' => 'gpt-4o',
                'temperature' => 0,
                'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an AI that analyzes emails. Your job is to detect the user\'s intent (refund, unsubscribe, or other), and the language of the email in 2-letter ISO code. Output only a JSON object like: {"language":"en","intent":["refund","unsubscribe","other"]}.',
                ],
                [
                        'role' => 'user',
                        'content' => $this->emailContent,
                    ],
                ],
            ];

            $response = $gptService->chat($params);

            $content = $response['choices'][0]['message']['content'] ?? null;

            if ($content) {
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {

                    dd($decoded);
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
