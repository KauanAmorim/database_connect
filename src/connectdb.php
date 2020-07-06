<?php

namespace connection;

class connectdb 
{
	private $database;
    private $db_name;
    
    private $json;
    private $config;

    public function __construct($config_path, $db_name = null)
    {
        $this->db_name = $db_name;
		
		if(file_exists($config_path)){
			$this->json = \file_get_contents($config_path);
			$this->config = (array) json_decode($this->json);
		} else {
			throw new \Exception('File not exist');
		}
	}

	/**
	 * This method set a connection.
	 * 
	 * @param Object $connection -> Instance of PDO Object.
	 * @return Boolean -> True if success and False if failure.
	 */
	public function setConnection(\PDO $connection)
	{
		if($connection instanceof \PDO){
			$this->database = $connection;
			return true;
		} else {
			throw new \Exception('This is not a PDO instance');
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

			if(!isset($this->database) && empty($this->database)){

				$json = \file_get_contents("config/config.json");
				$config = (array) json_decode($json);
			
				if(array_key_exists($this->db_name, $this->config)){
					return $this->tryConnection($debug);
				} else {
					throw new \Exception('Error database do not exist');
				}
			} else {
				return $this->database;
			}
        } 
        throw new \Exception('Error database name is not defined');
    }

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
	 * This method support just mysql connections
	 * 
	 * @param Array $config -> database config.
	 * @return Object -> if success.
	 * @return Boolean -> if failure.
	 */
	private function tryConnection()
	{
		try {

			$this->database = new \PDO(
                "mysql:
                host=".$this->config[$this->db_name]->host.";
                dbname=".$this->db_name,
                $this->config[$this->db_name]->user, 
                $this->config[$this->db_name]->pass,
				[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
			);

			return $this->database;
			
		} catch (\Exception $th) {

			$connection_data = $this->getConnectionData();
			$ObjError = new \stdClass;
			$ObjError->trace = $th->getTrace();
			$ObjError->message = $th->getTrace();
			$ObjError->ConnectionData = $connection_data['data'];
			$ObjError->return = false;

			return $ObjError;
		}
	}
}