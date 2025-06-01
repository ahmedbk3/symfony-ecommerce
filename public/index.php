<?php
// public/index.php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

// 1) Tell PHP not to report E_DEPRECATED or E_STRICT
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', '1'); // still show real errors

// 2) Include Composer’s autoloader
require dirname(__DIR__).'/vendor/autoload.php';

// 3) Enable Symfony’s Debug with the same error mask
Debug::enable(E_ALL & ~E_DEPRECATED & ~E_STRICT);

// 4) Instantiate and handle the Kernel manually (bypassing autoload_runtime.php)
$env = $_SERVER['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));

$kernel = new Kernel($env, $debug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
