<?php

/**
 * This is the database class. It is used to connect to the database and perform the CRUD operations.
 * @package    customforms
 */

// Include the init file which contains the external database credentials
require_once ('init.php');
class Database {
    private string $host = DB_HOST;
    private string $username = DB_USER;
    private string $pass = DB_PASS;
    private string $dbname = DB_NAME;
    private PDO $dbh;
    private $stmt;
    private string $error;

	/**
	 * Constructor to connect to the database
	 * @return void
	 */
    function __construct() {
        // Set DSN
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;

        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($dsn, $this->username, $this->pass, $options);
        } catch (PDOException $pDOException) {
            //throw $th;
            $this->error = $pDOException->getMessage();
            echo $this->error;
        }
    }

	/**
	 * This function is used to prepare the query
	 * @param $sql
	 * @return void
	 */

    public function query ($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }
	/**
	 * This function is used to bind the parameters to the query
	 * @param $params
	 * @param $value
	 * @param $type
	 * @return void
	 */
    public function bind($params, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }
        $this->stmt->bindValue($params, $value, $type);
    }

	/**
	 * This function is used to execute the query
	 * @return mixed
	 */
    public function execute () {
        return $this->stmt->execute();
    }

	/**
	 * This function is used to get the result set
	 * @return mixed
	 */
    public function resultSet (){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

	/**
	 * This function is used to get the single record
	 * @return mixed
	 */

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
	/**
	 * This function is used to get the row count
	 * @return mixed
	 */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
}
