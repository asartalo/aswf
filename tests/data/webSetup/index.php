<?php

require realpath(__DIR__ . '/../../../') . '/vendor/autoload.php';

use Asar\Application\Loader;

$appConfig = realpath(__DIR__ . '/../../') . '/Asar/Tests/Functional/ExampleApp/config.yml';
Loader::runApp(
    $appConfig,
    $_SERVER,
    $_GET,
    $_POST,
    $_FILES,
    $_COOKIE,
    $_ENV
);