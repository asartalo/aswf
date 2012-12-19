<?php

$c = $container;

$c['exampleapp.model.blog.class'] = 'Asar\Tests\Functional\ExampleApp\Model\Blog';
$c['exampleapp.resource.blog.class'] = 'Asar\Tests\Functional\ExampleApp\Resource\Blog';


$c->scope('request');

$c['exampleapp.resource.blog'] = function($c) {
    return new $c['exampleapp.resource.blog.class']($c['request.page'], $c['exampleapp.model.blog']);
};

$c['exampleapp.model.blog'] = function($c) {
    return new $c['exampleapp.model.blog.class'];
};