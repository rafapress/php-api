<?php

namespace Models;


class Cidades
{

	public $table = 'cidades';
	private $connection;

	public function __construct()
	{
		$this->connection = \Database::getConnection();
	}

	public function getEstados()
	{
		return $this->connection->query("SELECT cid_uf as uf, cid_estado as nome FROM {$this->table} GROUP BY cid_uf")->fetchAll(\Database::FETCH_OBJ);
	}
	public function getByUf($uf)
	{
		$sql = "SELECT
					cid_id as id,
					cid_nome as nome
				FROM
					{$this->table}
				WHERE
					cid_uf = :uf";

		$query = $this->connection->prepare($sql);
		$query->execute(array(':uf' => $uf));

		return $query->fetchAll(\Database::FETCH_OBJ);
	}
}
