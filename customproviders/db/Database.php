<?php

require_once '../../config.php';


class Database
{
	private string $host;
	private string $username;
	private string $pass;
	private string $dbname;
	private string $table;

	public PDO $pdo;
	private string $error;

	private $stmt;

	/**
	 * Constructor to connect to the database
	 * @return void
	 * @throws \dml_exception
	 */
	public function __construct() {
		$config = get_config('auth_db');
		$this->host = $config->host;
		$this->username = $config->user;
		$this->pass = $config->pass;
		$this->dbname = $config->name;
		$this->table = $config->table;

		$dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;

		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try {
			$this->pdo = new PDO($dsn, $this->username, $this->pass, $options);
		} catch (PDOException $pDOException) {
			$this->error = $pDOException->getMessage();
			echo $this->error;
		}
	}

	/**
	 * This function is used to prepare the query
	 * @param $sql
	 * @return void
	 */
	public function query ($sql): void
	{
		$this->stmt = $this->pdo->prepare($sql);
	}

	/**
	 * This function is used to bind the parameters to the query
	 * @param $params
	 * @param $value
	 * @param $type
	 * @return void
	 */
	public function bind($params, $value, $type = null): void
	{
		if (is_null($type)) {
			$type = match (true) {
				is_int($value) => PDO::PARAM_INT,
				is_bool($value) => PDO::PARAM_BOOL,
				is_null($value) => PDO::PARAM_NULL,
				default => PDO::PARAM_STR,
			};
		}
		$this->stmt->bindValue($params, $value, $type);
	}

	/**
	 * This function is used to execute the query
	 * @return mixed
	 */
	public function execute (): mixed
	{
		return $this->stmt->execute();
	}

	/**
	 * This function is used to get the result set
	 * @return mixed
	 */
	public function resultSet (): mixed
	{
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * This function is used to get the single result
	 * @return mixed
	 */
	public function single (): mixed
	{
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}
}
