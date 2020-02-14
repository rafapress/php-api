<?php

define( 'IS_LOCAL', strpos($_SERVER['HTTP_HOST'], 'localhost') !== false );

require '../../vendor/autoload.php';

$app = new Slim\App([
	'settings' => [
		// Mostra os erros quando for localhost
        'displayErrorDetails' => strpos($_SERVER['SERVER_NAME'], 'localhost') != false
    ]
]);

include_once __DIR__ . '/cadastro.php';
include_once __DIR__ . '/cidades.php';

// $app->get('/server', function ($request, $response, $args) {
// 	return $response->withJSON($_SERVER);
// });

$app->run();
