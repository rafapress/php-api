<?php

namespace Models;


class Escolas
{

	public $table = 'escolas';
	private $connection;

	public function __construct()
	{
		$this->connection = \Database::getConnection();
	}

	public function getByCidade($cidId)
	{
		$sql = "SELECT
					esc_id as id,
					cid_nome as cidade,
					esc_nome as nome
				FROM
					{$this->table}
					JOIN cidades
					  ON esc_cidade = cid_id
				WHERE
					esc_cidade = :cidade";

		$query = $this->connection->prepare($sql);
		$query->execute(array(':cidade' => $cidId));

		return $query->fetchAll(\Database::FETCH_OBJ);
	}

}
