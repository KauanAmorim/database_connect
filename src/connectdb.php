<?php

namespace connection;

class connectdb 
{
	private $db;
    private $db_name;
    
    private $json;
    private $config;

    public function __construct($config_path, $db_name = null)
    {
        $this->db_name = $db_name;
        
        $this->json = \file_get_contents($config_path);
		$this->config = (array) json_decode($this->json);
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
			
				if(array_key_exists($this->db_name, $this->config)){
					return $this->tryConnection($debug);
				}
			} else {
				return $this->db;
			}
        } 
        return false;
    }
    
    /**
     * This method get data to connec with db.
     */
    public function getConnectionData()
    {
        return [
            'data' => [
                'db_name' => $this->db_name,
                'host' => $this->config[$this->db_name]->host,
                'user' => $this->config[$this->db_name]->user,
                'pass' => $this->config[$this->db_name]->pass
            ]
        ];
    }

    /**
     * This function make actions of transactions.
     * @param String $action -> name of the action.
     * @return Boolean false if action doesn't exist.
     */
    public function transctions(String $action)
    {
        switch ($action) {
            case 'beginTransaction':
                return $this->db->$action();
                break;
            case 'commit':
                return $this->db->$action();
                break;
            case 'rollback':
                return $this->db->$action();
                break;
            default:
                return false;
                break;
        }
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
	private function tryConnection($debug)
	{
		try {

			$this->db = new \PDO(
                "mysql:
                host=".$this->config[$this->db_name]->host.";
                dbname=".$this->db_name,
                $this->config[$this->db_name]->user, 
                $this->config[$this->db_name]->pass,
				array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
			);

			return $this->db;
			
		} catch (\Exception $th) {

			if($debug == true){
                $connection_data = $this->getConnectionData();
				return [
                    'message' => $th->getMessage(),
                    'data' => $connection_data['data'],
					'return' => false
				];
			}
			return false;
		}
	}
}