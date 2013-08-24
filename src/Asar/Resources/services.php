<?php

$c = $container;

// Define scopes first
$c->createScope('application');
$c->createScope('session', 'application');
$c->createScope('request', 'session');

$c['serviceContainer'] = $c;

// parameters
$c['application.config.file'] = '';
$c['application.path'] = '';

$c['asar.config.yamlImporter.class'] = 'Asar\Config\YamlImporter';
$c['asar.config.config.class'] = 'Asar\Config\Config';
$c['asar.filesystem.utility.class'] = 'Asar\FileSystem\Utility';
$c['asar.routing.nodesBuilder.class'] = 'Asar\Routing\NodeTreeBuilder';
$c['asar.templateEngine.php.class'] = 'Asar\Template\Engine\PhpEngine';
$c['asar.responseExporter.class'] = 'Asar\Http\Message\ResponseExporter';
$c['asar.requestFactory.class'] = 'Asar\Http\Message\RequestFactory';

$c['asar.resource.generic.notfound.class'] = 'Asar\Http\Resource\Generic\NotFound';

$c['application.class'] = 'Asar\Application\Application';
$c['application.router.class'] = 'Asar\Routing\Router';
$c['application.routeNodes.class'] = 'Asar\Routing\Node';
$c['application.nodeNavigator.class'] = 'Asar\Routing\NodeNavigator';
$c['application.resourceFactory.class'] = 'Asar\Http\Resource\ResourceFactory';
$c['application.resourceResolver.class'] = 'Asar\Http\Resource\ResourceResolver';
$c['application.templateAssembler.class'] = 'Asar\Template\TemplateAssembler';
$c['application.templateEngineRegistry.class'] = 'Asar\Template\Engine\EngineRegistry';
$c['application.dispatchEntry.class'] = 'Asar\Application\DispatchEntry';
$c['application.templateEngineRegistryHelper.class'] = 'Asar\Template\Engine\RegistryHelper';

$c['session.store.service'] = 'session.arrayStore';
$c['session.store.class'] = 'Asar\Session\ArrayStore';
$c['session.defaultStore.class'] = 'Asar\Session\DefaultStore';

$c['request.response.class'] = 'Asar\Http\Message\Response';
$c['request.resourceDispatcher.class'] = 'Asar\Http\Resource\Dispatcher';
$c['request.page.class'] = 'Asar\Content\Page';
$c['request.route.class'] = 'Asar\Routing\Route';

// services
$c['yaml.parser'] = function($c) {
    return new Symfony\Component\Yaml\Parser;
};

$c['asar.config.yamlImporter'] = function($c) {
    return new $c['asar.config.yamlImporter.class']($c['yaml.parser']);
};

$c['asar.routing.nodesBuilder'] = function($c) {
    return new $c['asar.routing.nodesBuilder.class'];
};

$c['asar.filesystem.utility'] = function($c) {
    return new $c['asar.filesystem.utility.class'];
};

$c['asar.templateEngine.php'] = function($c) {
    return new $c['asar.templateEngine.php.class'];
};

$c['asar.resource.generic.notfound'] = function($c) {
    return new $c['asar.resource.generic.notfound.class'];
};

$c['asar.config.default'] = function($c) {
    return new $c['asar.config.config.class'](
        $c['asar.config.default.path'], array('importers' => array($c['asar.config.yamlImporter']))
    );
};

$c['asar.config.default.path'] = function($c) {
    $service = $c['asar.framework.utility'];
    return $service->getResourcePath('config.default.yml');
};

$c['asar.responseExporter'] = function($c) {
    return new $c['asar.responseExporter.class'];
};

$c['asar.requestFactory'] = function($c) {
    return new $c['asar.requestFactory.class'];
};

// Define services in application scope
$c->scope('application');

$c['application'] = function($c) {
    return new $c['application.class']($c['application.dispatchEntry']);
};

$c['application.dispatchEntry'] = function($c) {
    return new $c['application.dispatchEntry.class']($c);
};

$c['application.config'] = function($c) {
    return new $c['asar.config.config.class'](
        $c['application.config.file'],
        array(
            'importers' => array($c['asar.config.yamlImporter']),
            'extends' => $c['asar.config.default']
        )
    );
};

$c['application.routes'] = function($c) {
    $service = $c['application.config'];
    return $service->get('routes');
};

$c['application.routeNodes'] = function($c) {
    $service = $c['asar.routing.nodesBuilder'];
    return $service->build($c['application.routes']);
};

$c['application.nodeNavigator'] = function($c) {
    return new $c['application.nodeNavigator.class']($c['application.routeNodes']);
};

$c['application.router'] = function($c) {
    return new $c['application.router.class']($c['application.nodeNavigator']);
};

$c['application.resourceResolver'] = function($c) {
    return new $c['application.resourceResolver.class'](
        $c['application.path'], $c['application.config']
    );
};

$c['application.resourceFactory'] = function($c) {
    return new $c['application.resourceFactory.class'](
        $c, $c['application.resourceResolver']
    );
};

$c['application.templateAssembler'] = function($c) {
    return new $c['application.templateAssembler.class'](
        $c['application.path'],
        $c['application.templateEngineRegistry'],
        $c['asar.filesystem.utility']
    );
};

$c['application.templateEngineRegistry.pristine'] = function($c) {
    return new $c['application.templateEngineRegistry.class'];
};

$c['application.templateEngineRegistry'] = function($c) {
    $service = $c['application.templateEngineRegistryHelper'];
    return $service->registerEngines($c['application.template.enginesList']);
};

$c['application.template.enginesList'] = function($c) {
    $service = $c['application.config'];
    return $service->get('template.engines');
};

$c['application.templateEngineRegistryHelper'] = function($c) {
    return new $c['application.templateEngineRegistryHelper.class'](
        $c['application.templateEngineRegistry.pristine'], $c
    );
};


// Define services in session scope
$c->scope('session');

$c['session.store'] = function($c) {
    return $c[$c['session.store.service']];;
};

$c['session.arrayStore'] = function($c) {
    return new $c['session.store.class'](array());
};

$c['session.defaultStore'] = function($c) {
    return new $c['session.defaultStore.class'];
};


// Define services in request scope
$c->scope('request');

$c['request.page.template'] = ''; // loaded by template finder
$c['request.request'] = null; // loaded as scope parameter

$c['request.resource'] = function($c) {
    $service = $c['application.resourceFactory'];
    return $service->getResource($c['request.route']);
};

$c['request.resource.default'] = $c->auto('request.resource.class');

$c['request.response'] = function($c) {
    $service = $c['request.resourceDispatcher'];
    return $service->handleRequest($c['request.request']);
};

$c['request.resourceDispatcher'] = function($c) {
    return new $c['request.resourceDispatcher.class']($c['request.resource']);
};

$c['request.route'] = function($c) {
    $service = $c['application.router'];
    return $service->route($c['request.path']);
};

$c['request.path'] = function($c) {
    $service = $c['request.request'];
    return $service->getPath();
};

$c['request.page'] = function($c) {
    return new $c['request.page.class'](
        $c['application.templateAssembler'],
        $c['request.route'],
        $c['request.request']
    );
};