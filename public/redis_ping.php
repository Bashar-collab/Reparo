<?php

require 'vendor/autoload.php';

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => getenv('REDIS_HOST'),
    'port'   => getenv('REDIS_PORT'),
    'password' => getenv('REDIS_PASSWORD'),
]);

try {
    $pong = $client->ping();
    echo "Redis ping response: " . $pong;
} catch (Exception $e) {
    echo "Failed to connect to Redis: " . $e->getMessage();
}
