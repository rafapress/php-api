<?php

$app->post('/cadastro/validate/email', function ($request, $response, $args) {

	$professoresModel = new \Models\Professores();

	$data = $request->getParsedBody();

	$email = $data['email'];

	$out = [
		// 'errorMessage' => mensagem em string
		// 'error' => existe errorMessage
		// 'success' => !error
	];

	if ( !$email ) {
		$out['errorMessage'] = 'E-Mail não foi inserido';
	}
	else if ( $professoresModel->hasEmail($email) ) {
		$out['errorMessage'] = 'E-Mail já utilizado';
	}

	$out['error'] = isset( $out['errorMessage'] );
	$out['success'] = !$out['error'];

	return $response->withJSON( $out );

});

$app->post('/cadastro/validate/telefone', function ($request, $response, $args) {

	$professoresModel = new \Models\Professores();

	$data = $request->getParsedBody();

	$telefone = $data['telefone'];

	$out = [
		// 'errorMessage' => 'mensagem'
		// 'error' => errorMessage
		// 'success' => !error
	];

	if ( !$telefone ) {
		$out['errorMessage'] = 'Telefone não foi inserido';
	}
	else if ( $professoresModel->hasTelefone($telefone) ) {
		$out['errorMessage'] = 'Telefone já utilizado';
	}

	$out['error'] = isset( $out['errorMessage'] );
	$out['success'] = !$out['error'];

	return $response->withJSON( $out );

});

$app->post('/cadastro/enviar', function ($request, $response, $args) {

	$professoresModel = new \Models\Professores();

	$data = $request->getParsedBody();

	return $response->withJSON( $professoresModel->save($data) );

});
