<?php

require realpath(__DIR__ . '/../../../') . '/vendor/autoload.php';

use Asar\Application\Loader;

if (!isset($_SESSION)) {
  $_SESSION = array();
}

$appConfig = realpath(__DIR__ . '/../../') . '/Asar/Tests/Functional/ExampleApp/config.yml';
Loader::runApp(
    $appConfig,
    $_SERVER,
    $_GET,
    $_POST,
    $_FILES,
    $_SESSION,
    $_COOKIE,
    $_ENV
);