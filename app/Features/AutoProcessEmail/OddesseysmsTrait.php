<?php

namespace App\Features\AutoProcessEmail;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

trait OddesseysmsTrait
{
    abstract public function getBaseUri(): string;

    public function getSubscriptions(string $mobileNumber): array
    {
        try{
            $response = Http::timeout($this->timeout)
                ->retry($this->tries, 100)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post('https://portal.telcosupport.com/phpinfo.php', [
                    'token' => 'nakuit',
                    'endpoint' => $this->getBaseUri() . "/lookup?msisdn=" . $this->normalize($mobileNumber),
                    'method' => 'POST',
                ])
                ->throw();

            return $response->json();
        } catch(RequestException $e) {
            $status = $e->response->status();
            $body = $e->response->json();

            if ($status === 400) {
                return $body;
            }

            throw $e;
        }
    }
    
    public function unsubscribe(string $mobileNumber): array
    {
        try{
            $response = Http::timeout($this->timeout)
                ->retry($this->tries, 100)
                ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post('https://portal.telcosupport.com/phpinfo.php', [
                'token' => 'nakuit',
                'endpoint' => $this->getBaseUri() . "/unsubscribe?msisdn=" . $this->normalize($mobileNumber),
                'method' => 'POST',
            ])
            ->throw();

            return $response->json();
        } catch(RequestException $e) {
            $status = $e->response->status();
            $body = $e->response->json();

            if ($status === 400) {
                return $body;
            }

            throw $e;
        }
    }
}
