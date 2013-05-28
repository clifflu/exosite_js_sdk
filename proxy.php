<?php

/**
 * Proxy.php
 * 
 * A PHP proxy to m2.exosite.com, as they don't accept XHR requests from other origins.
 * 
 */

require_once('include/http_response_code.php');
require_once('include/json_encode_unescaped_unicode.php');

// Keys to keep from the request header, and pass to remote server
$headers_keep = array(
    'accept',
    'content-type',
    'x-exosite-cik',
    'x-forwarded-for'
);

$headers_in = getallheaders();
$headers_out = array();
foreach($headers_in as $key => $value) {
    if (in_array(strtolower($key), $headers_keep)) {
        // $key cases are preserved
        $headers_out[] = "$key: $value";
    }
}

// Headers a proxy should send
if (isset($headers_out['X-Forwarded-For']))
    $headers_out['X-Forwarded-For'] .= ', ';
else 
    $headers_out['X-Forwarded-For'] = '';

$headers_out['X-Forwarded-For'] .= $_SERVER['REMOTE_ADDR'];

// curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, urldecode($headers_in['X-Server-URL']));

// log request headers
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

// follow redirects, max redir = 10
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

// return response, strip response headers
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// set request headers
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_out);

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    curl_setopt($ch, CURLOPT_POST, 1);

    // convert $param to string, or PHP will override Content-Type as 'multipart/form-data'
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query ($_REQUEST));    
}

$ret = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// bubbles http code
if ($http_code) {
    http_response_code($http_code);
}

if ('GET' === $_SERVER['REQUEST_METHOD'] && $http_code == '200') {
    // convert url-encoded query string to json; keys and values not urlencoded
    parse_str($ret, $return);
    echo json_encode_unescaped_unicode($return);
}