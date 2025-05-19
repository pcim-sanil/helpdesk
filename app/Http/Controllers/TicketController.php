<?php

namespace App\Http\Controllers;

use App\Services\GptService;

class TicketController extends Controller
{
    protected function checkIntent(string $content): array
    {
        $gptService = new GptService();
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
                        'content' => $content,
                    ],
                ],
            ];

            $response = $gptService->chat($params);

            $content = $response['choices'][0]['message']['content'] ?? null;

            if ($content) {
                $decoded = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {

                    return $decoded;
                } else {
                    return ['error' => 'Invalid JSON format in response.'];
                }
            } else {
                return ['error' => 'No content returned from API.'];
            }
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function index()
    {
        dd('yes');
    }
}
