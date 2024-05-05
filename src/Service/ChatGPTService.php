<?php


namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ChatGPTService
{
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function chat(string $prompt): string
    {
        $url = 'https://api.openai.com/v1/completions';

        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo', // Modèle recommandé
                    'prompt' => $prompt,
                    'max_tokens' => 50,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \RuntimeException('Erreur lors de la requête à l\'API ChatGPT : ' . $response->getContent(false));
            }

            $responseData = $response->toArray();
            return $responseData['choices'][0]['text'];

        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Erreur de transport lors de la requête à l\'API ChatGPT : ' . $e->getMessage());
        }
    }
}


