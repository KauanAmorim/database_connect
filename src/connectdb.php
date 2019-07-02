<?php

namespace connection;

class connectdb 
{
	private $db;
	private $config;

    public function __construct($db_name = null)
    {
        if($db_name){

			$json = \file_get_contents("config/config.json");
			$this->config = (array) json_decode($json);
		
			if(array_key_exists($db_name, $this->config)){

				try {
					$this->db = new PDO(
						"mysql:host=".$this->config[$db_name]->host.";dbname=".$db_name,
						$this->config[$db_name]->user, $this->config[$db_name]->pass
					);

					if(!is_object($this->db)){
						throw new Exception('Erro ao se connectar com o banco de dados');
					} else {
						return true;
					}
					
				} catch (\Exception $th) {
					return $th->getMessage();
				}
			}
        } 
        return false;
    }
}