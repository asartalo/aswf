<?php

$src_path = realpath(__DIR__ . '/../src');
$vendor_path = realpath(__DIR__ . '/../vendor');
require_once $vendor_path . '/AsarClassLoader/src/Asar/ClassLoader.php';
$classLoader = new \Asar\ClassLoader('Asar\Tests', __DIR__);
$classLoader->register();
$classLoader = new \Asar\ClassLoader('Asar', $src_path);
$classLoader->register();
$classLoader = new \Asar\ClassLoader('Pimple', $vendor_path . '/Pimple/lib');
$classLoader->register();

