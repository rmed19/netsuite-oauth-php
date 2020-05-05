<?php

use GuzzleHttp\{Client, HandlerStack, RequestOptions, Subscriber\Oauth\Oauth1};

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/configs.php';

$stack = HandlerStack::create();

$middleware = new Oauth1([
    'consumer_key' => NETSUITE_CONSUMER_KEY,
    'consumer_secret' => NETSUITE_CONSUMER_SECRET,
    'signature_method' => Oauth1::SIGNATURE_METHOD_HMAC,
    'token' => NETSUITE_TOKEN_ID,
    'token_secret' => NETSUITE_TOKEN_SECRET,
    'realm' => NETSUITE_ACCOUNT,
]);

$stack->push($middleware);

$client = new Client([
    'base_uri' => NS_HOST,
    'handler' => $stack,
    'auth' => 'oauth',
]);

try {
    $response = $client->get('/services/rest/record/v1/metadata-catalog/customer', [
        RequestOptions::HEADERS => [
            'Content-Type' => 'application/json',
            'Accept'=> 'application/schema+json',
        ]
    ]);

    print_r(json_decode($response->getBody()->getContents(), true));
    print('OK').PHP_EOL;
} catch (\GuzzleHttp\Exception\ClientException $exception) {
    print('ERROR').PHP_EOL;
}