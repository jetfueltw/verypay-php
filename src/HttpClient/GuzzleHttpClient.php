<?php

namespace Jetfuel\Verypay\HttpClient;

use GuzzleHttp\Client;

class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * GuzzleHttpClient constructor.
     *
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/').'/';
        $this->client = new Client();
    }

    /**
     * POST request.
     *
     * @param string $uri
     * @param string $data
     * @return string
     */
    public function post($uri, $data)
    {
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
        ];

        $response = $this->client->request('POST', $this->baseUrl.$uri, [
            'headers' => $headers,
            'body'    => $data,
        ]);

        return $response->getBody()->getContents();
    }
}
