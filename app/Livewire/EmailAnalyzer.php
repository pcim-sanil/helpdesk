<?php

namespace App\Livewire;

use App\Services\GptService;
use Livewire\Component;

class EmailAnalyzer extends Component
{
    public $emailContent = '';

    public $analysisResult = null;

    public function analyzeEmail()
    {
        $gptService = new GptService;
        try {
            $params = [
                'model' => 'gpt-4o',
                'temperature' => 0,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an AI that analyzes emails. Your job is to detect the sender\'s intention from the email content. (refund, unsubscribe, or other). Output only a JSON object like: {"intent":["refund","unsubscribe"]} or {"intent":["other"]} or {"intent":["refund"]}.',
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
                    $this->analysisResult = $decoded;
                } else {
                    $this->analysisResult = ['error' => 'Invalid JSON format in response.'];
                }
            } else {
                $this->analysisResult = ['error' => 'No content returned from API.'];
            }
        } catch (\Exception $e) {
            $this->analysisResult = ['error' => 'Exception occurred: '.$e->getMessage()];
        }
    }

    public function render()
    {
        return view('livewire.email-analyzer')->layout('components.layouts.auth.simple');
    }
}
