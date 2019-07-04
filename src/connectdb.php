<?php

namespace connection;

class connectdb 
{
	private $db;
	private $db_name;

    public function __construct($db_name = null)
    {
		$this->db_name = $db_name;
	}

	/**
	 * This method set a connection.
	 * 
	 * @param Object $connection -> Instance of PDO Object.
	 * @return Boolean -> True if success and False if failure.
	 */
	public function setConnection($connection)
	{
		if($connection instanceof \PDO){
			$this->db = $connection;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * This method makes a connection to database.
	 *
	 * @param Boolean $debug -> for debug failure - True -> @return Array.
	 * @return Object -> Connection Object.
	 * @return Boolean -> False if don't set the db_name.
	 */
	public function getConnection($debug = false)
	{
        if($this->db_name){

			if(!isset($this->db) && empty($this->db)){

				$json = \file_get_contents("config/config.json");
				$config = (array) json_decode($json);
			
				if(array_key_exists($this->db_name, $config)){
					return $this->tryConnection($config, $debug);
				}
			} else {
				return $this->db;
			}
        } 
        return false;
	}

	/**
	 * Make a Begin Transaction.
	 * 
	 * @return Boolean -> True if success and False if failure.
	 */
	public function beginTransaction()
	{
		return $this->db->beginTransaction();
	}

	/**
	 * Make a Rollback.
	 * 
	 * @return Boolean -> True if success and False if failure.
	 */
	public function rollBack()
	{
		return $this->db->rollBack();
	}

	/**
	 * This method execute querys
	 * 
	 * @param String $query -> sql to consult in database.
	 * @param String $return -> What returns.
	 * @return Mixed -> Return the result of sql execution.
	 */
	public function execute($query, $return = null)
	{
		if(!empty($query)){

			$statement = $this->db->prepare($query);
			if($statement->execute()){
				return $this->selectReturn($return, $statement);
			}
		}
		return false;
	}

	/**
	 * This method is an axulixiar method to execute sql.
	 * 
	 * @param String $return -> What returns.
	 * @param Object $statement -> Object statement.
	 * @return Mixed -> Return the result of sql execution.
	 */
	private function selectReturn($return, $statement)
	{
		switch ($return) {
			case 'fetch':
				return $statement->fetch();
				break;
			
			case 'fetchAll':
				return $statement->fetchAll();
				break;

			case 'lastInsertId':
				return $this->db->lastInsertId();
				break;

			case 'rowCount':
				return $statement->rowCount();
				break;

			default:
				return true;
				break;
		}
	}

	/**
	 * This method try connect with an database.
	 * 
	 * @param Array $config -> database config.
	 * @return Object -> if success.
	 * @return Boolean -> if failure.
	 */
	private function tryConnection($config)
	{
		try {

			$this->db = new \PDO(
				"mysql:host=".$config[$this->db_name]->host.";dbname=".$this->db_name,
				$config[$this->db_name]->user, $config[$this->db_name]->pass, 
				array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
			);

			return $this->db;
			
		} catch (\Exception $th) {
			if($debug == true){
				return [
					'message' => $th->getMessage(),
					'return' => false
				];
			}
			return false;
		}
	}
}