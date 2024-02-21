<?php

namespace App\Services;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class Elasticsearch
{

    private static ?Client $instance = null;

    public static function getInstance(string $host): Client
    {
        if (is_null(self::$instance)) {
            self::$instance = ClientBuilder::create()
                ->setHosts([$host])
                ->build();
        }

        return self::$instance;
    }
}
