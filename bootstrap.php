<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Woeplanet\Utils\Enpathify as Enpathify;

$config = new \Woeplanet\Resolver\Config();

$settings = [
    'settings' => $config->get_config()
];
$app = new \Slim\App($settings);

//=============================================================================
// Dependency Injection

$container = $app->getContainer();

$container->register(new \Woeplanet\Resolver\Dependencies\LoggerProvider());

//=============================================================================
// Middleware

$app->add(new \Woeplanet\Resolver\Middleware\CORSHandler());

//=============================================================================
// Routes

$app->get('/', function(ServerRequestInterface $request, ResponseInterface $response, $args) {
    $data = [
        'code' => 400,
        'message' => 'Missing WOEID'
    ];
    return $response = $response->withStatus($data['code'])
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->write(json_encode($data, JSON_PRETTY_PRINT));
});

$app->get('/{woeid}', function(ServerRequestInterface $request, ResponseInterface $response, $args) {
    $woeid = intval($args['woeid']);
    $root_uri = $this->get('settings')['root-uri'];
    $uri = sprintf('%s/%s/%d.geojson', $root_uri, Enpathify::enpathify($woeid), $woeid);
    $code = 301;

    return $response->withStatus($code)->withHeader('Location', $uri);
});

?>
