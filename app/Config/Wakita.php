<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Wakita extends BaseConfig
{
    public string $baseUrl;
    public string $apiKey;
    public string $apiSecret;
    public int $timeout;

    public function __construct()
    {
        parent::__construct();

        $this->baseUrl = rtrim(
            (string) env('wakita.baseUrl', 'https://wakita.aminsproject.com/api/v1'),
            '/'
        );

        $this->apiKey = (string) env('wakita.apiKey', '');

        $this->apiSecret = (string) env('wakita.apiSecret', '');

        $this->timeout = max(
            1,
            (int) env('wakita.timeout', 30)
        );
    }
}
