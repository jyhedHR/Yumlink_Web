<?php
namespace App\Service;

use GuzzleHttp\Client;

class EdamamApiService
{
    private $client;
    private $appId;
    private $appKey;

    public function __construct($appId, $appKey)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.edamam.com/api/nutrition-details?app_id=58aea65e&app_key=8857ecdc438404779d04b57881f1a456',
        ]);
        $this->appId = $appId;
        $this->appKey = $appKey;
    }

    public function searchFood($query)
    {
        $response = $this->client->request('GET', 'parser', [
            'query' => [
                'ingr' => $query,
                'app_id' => $this->appId,
                'app_key' => $this->appKey,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
