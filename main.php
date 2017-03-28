<?php

$config = json_decode(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.json'));

require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Credentials\Credentials;

$credentials = new Credentials($config->accessid, $config->secret);

$proxy = array(
	'@http'    => [
        'proxy' => 'http://'.$config->proxyuname.":".$config->proxyupass.'@'.$config->proxyurl
    ],
    '@https'    => [
        'proxy' => 'http://'.$config->proxyuname.":".$config->proxyupass.'@'.$config->proxyurl
    ]
);

$options = [
    'version'     => 'latest',
    'region'      => 'us-east-1',
    'credentials' => $credentials,
    'validate' => false,
];

d($options);

$s3 = new S3Client($options);

$command = $s3->getCommand('ListBuckets', $proxy);

$command['MaxKeys']=200;

d($command);
$result = $s3->execute($command);
d($result);
/*
    'version'     => 'latest',
    'region'      => 'us-east-1',
    'credentials' => $credentials,

    'request.options'    => [
        'proxy' => [
            'http' => $config->proxyurl,
            'https' => $config->proxyurl,
        ]
    ]
*/