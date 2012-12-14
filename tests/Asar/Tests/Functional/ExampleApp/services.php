<?php

$c = $container;

$c['exampleapp.model.blog.class'] = 'Asar\Tests\Functional\ExampleApp\Model\Blog';
$c['exampleapp.resource/Blog.class'] = 'Asar\Tests\Functional\ExampleApp\Resource\Blog';


$c->scope('request');

$c['exampleapp.resource/Blog'] = function($c) {
    return new $c['resource/blog.class']($c['request.page'], $c['example.model.blog']);
};

$c['exampleapp.model.blog'] = function($c) {
    return new $c['exampleapp.model.blog.class'];
};