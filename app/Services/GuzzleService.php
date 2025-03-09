<?php

namespace App\Services;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class GuzzleService
{
    protected $client;

    public function __construct()
    {
        // Ensure .env.local is loaded only if it exists
        if (file_exists(base_path('.env.local'))) {
            Dotenv::createImmutable(base_path(), '.env.local')->load();
        }

        $githubToken = env('GITHUB_PERSONAL_TOKEN');

        $this->client = new Client([
            'base_uri' => 'https://api.github.com/',
            'headers' => [
                'Authorization' => 'token ' . $githubToken,
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);
    }

    /**
     * Send a GET request with optional query parameters.
     */
    public function get($endpoint, array $params = [])
    {
        return $this->request('GET', $endpoint, ['query' => $params]);
    }

    /**
     * Send a POST request.
     */
    public function post($endpoint, array $data = [])
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    /**
     * Send a generic request.
     */
    private function request($method, $endpoint, array $options = [])
    {
        try {
            $response = $this->client->request($method, $endpoint, $options);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => 'Request Error: ' . $e->getMessage(),
                'status' => $e->getCode(),
            ];
        } catch (GuzzleException $e) {
            return [
                'error' => true,
                'message' => 'HTTP Client Error: ' . $e->getMessage(),
                'status' => $e->getCode(),
            ];
        }
    }
}
