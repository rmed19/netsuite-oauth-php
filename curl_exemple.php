<?php

require_once __DIR__.'/configs.php';

$url = NS_HOST.'/services/rest/record/v1/metadata-catalog/customer';
$oauth_nonce = md5(uniqid(mt_rand(), true));
$oauth_timestamp = time();
$oauth_signature_method = 'HMAC-SHA256';
$oauth_version = "1.0";

$base_string =
    "GET&" . urlencode($url) . "&" .
    urlencode(
        "oauth_consumer_key=" . NS_CONSUMER_KEY
        . "&oauth_nonce=" . $oauth_nonce
        . "&oauth_signature_method=" . $oauth_signature_method
        . "&oauth_timestamp=" . $oauth_timestamp
        . "&oauth_token=" . NS_TOKEN_ID
        . "&oauth_version=" . $oauth_version
    );

$sig_string = urlencode(NS_CONSUMER_SECRET) . '&' . urlencode(NS_TOKEN_SECRET);
$signature = base64_encode(hash_hmac("sha256", $base_string, $sig_string, true));

$auth_header = 'OAuth '
    . 'oauth_nonce="' . rawurlencode($oauth_nonce) . '", '
    . 'oauth_signature="' . rawurlencode($signature) . '", '
    . 'oauth_signature_method="' . rawurlencode($oauth_signature_method) . '", '
    . 'oauth_timestamp="' . rawurlencode($oauth_timestamp) . '", '
    . 'oauth_token="' . rawurlencode(NS_TOKEN_ID) . '", '
    . 'oauth_version="' . rawurlencode($oauth_version) . '", '
    . 'oauth_consumer_key="' . rawurlencode(NS_CONSUMER_KEY) . '", '
    . 'realm="' . NS_ACCOUNT . '"';

$cURLConnection = curl_init();

curl_setopt($cURLConnection, CURLOPT_URL, $url);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
    'Authorization: '.$auth_header,
    'Content-Type: application/json',
    'Accept: application/schema+json'
));
$response = curl_exec($cURLConnection);
curl_close($cURLConnection);

print_r(json_decode($response, true));