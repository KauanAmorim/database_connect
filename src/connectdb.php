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
	
	public function getConnection()
	{
        if($this->db_name){

			$json = \file_get_contents("config/config.json");
			$config = (array) json_decode($json);
		
			if(array_key_exists($this->db_name, $config)){
				return $this->tryConnection($config);
			}
        } 
        return false;
	}

	public function beginTransaction()
	{
		return $this->db->beginTransaction();
	}

	public function rollBack()
	{
		return $this->db->rollBack();
	}

	public function execute($query, $return = null)
	{
		if(!empty($query)){

			$statement = $this->db->prepare($query);
			if($statement->execute()){
				return $this->selectReturn($return);
			}
		}
		return false;
	}

	private function selectReturn($return)
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
			return $th->getMessage();
		}
	}
}