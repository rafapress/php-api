<?php

namespace Models;

class Professores
{

	public $table = 'professores';
	private $connection;

	public function __construct()
	{
		$this->connection = \Database::getConnection();
	}

	public function hasEmail($email)
	{
		$sql = "SELECT
					count(*) <> 0 as has_email
				FROM
					professores
				WHERE
					pro_email = :email";

		$query = $this->connection->prepare($sql);
		$query->execute(
			array(
				':email' => $email
			)
		);

		return boolval( $query->fetch(\Database::FETCH_COLUMN, 1) );
	}

	public function hasTelefone($telefone)
	{
		$sql = "SELECT
					count(*) <> 0 as has_telefone
				FROM
					professores
				WHERE
					pro_telefone = :telefone";

		$query = $this->connection->prepare($sql);
		$query->execute(
			array(
				':telefone' => $telefone
			)
		);

		return boolval( $query->fetch(\Database::FETCH_COLUMN, 1) );
	}

	public function getByEmail($email)
	{
		$sql = "SELECT
					*
				FROM
					professores
				WHERE
					pro_email = :email";

		$query = $this->connection->prepare($sql);
		$query->execute(
			array(
				':email' => $email
			)
		);

		return $query->fetchAll(\Database::FETCH_OBJ);
	}

	public function getByTelefone($telefone)
	{

		$sql = "SELECT * FROM professores WHERE pro_telefone = :telefone";

		$query = $this->connection->prepare($sql);
		$query->execute(array(':telefone' => $telefone));

		return $query->fetchAll(\Database::FETCH_OBJ);

	}

	public function save($obj)
	{

		$out = [];

		$required = [
			'nome',
			'email',
			'telefone',
			'estado',
			'cidade',
			'escola',
			'instituicao',
			'segmento',
			'modalidade',
			'formacao',
			'disciplina'
		];

		$fields = [];
		$values = [];

		foreach ($required as $requiredKey) {
			if ( !isset( $obj[$requiredKey] ) || empty( $obj[$requiredKey] ) ) {
				return [
					'success' => false,
					'message' => "Campo $requiredKey não inserido"
				];
			}

			// Pega o valor
			$value = $obj[$requiredKey];

			// Adiciona o campo à lista de campos
			$fields[] = 'pro_' . $requiredKey;

			// Coloca o valor em json, pois se for string, coloca aspas, e duas vezes para transformar em string e colocar aspas na string
			$values[]  = is_string($value) || is_numeric($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : json_encode(json_encode($value, JSON_UNESCAPED_UNICODE));

		}

		$sql = "INSERT INTO
					$this->table
					( " . implode(', ', $fields) . " )
				VALUES
					( " . implode(', ', $values) . " )";

		try {

			$id = $this->connection->prepare($sql)->execute([]);

			return [
				'success' => true,
				'inserted_id' => $id
			];
		} catch (\Exception $e) {
			return [
				'success' => false,
				'message' => 'Erro: ' . $e->getMessage()
			];
		}

	}

}
