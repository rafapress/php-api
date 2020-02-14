<?php


$app->get('/estados', function ($request, $response, $args) {

	$cidadesModel = new \Models\Cidades();

	return $response->withJSON( $cidadesModel->getEstados(), 200 );
});

/**
 * Pega as cidades do estado inserido
 */
$app->get('/{uf}/cidades', function ($request, $response, $args) {

	$uf = $args['uf'];

	$cidadesModel = new \Models\Cidades();

	return $response->withJSON( $cidadesModel->getByUf($uf) , 200);

});

/**
 * Pega as cidades do estado inserido
 */
$app->get('/cidade/{cidade}/escolas', function ($request, $response, $args) {

	$cidade = $args['cidade'];

	$escolasModel = new \Models\Escolas();

	return $response->withJSON( $escolasModel->getByCidade($cidade) , 200);

});
