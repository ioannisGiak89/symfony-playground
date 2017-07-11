<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/setup.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

// We need to re-use this logic so that we can allow local connections from private IPv4 addresses.
$isLocalIpv4 = function($ipv4) {
    $iplong = ip2long($ipv4);

    return (
        ($iplong >= ip2long("127.0.0.0") && $iplong <= ip2long("127.255.255.255"))
        || ($iplong >= ip2long("192.168.0.0") && $iplong <= ip2long("192.168.255.255"))
        || ($iplong >= ip2long("172.16.0.0") && $iplong <= ip2long("172.31.255.255"))
        || ($iplong >= ip2long("10.0.0.0") && $iplong <= ip2long("10.255.255.255"))
    );
};

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if ((isset($_SERVER['HTTP_CLIENT_IP']) && !$isLocalIpv4(@$_SERVER['HTTP_CLIENT_IP']))
    || (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !$isLocalIpv4(@$_SERVER['HTTP_X_FORWARDED_FOR']))
    || !(
        // Allow local usage:
        (@$_SERVER['REMOTE_ADDR'] === '::1' || php_sapi_name() === 'cli-server')
        || $isLocalIpv4(@$_SERVER['REMOTE_ADDR'])
    )
    && @$_SERVER['REMOTE_ADDR'] !== '213.1.221.154'
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

