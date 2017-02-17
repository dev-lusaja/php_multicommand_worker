<?php 

class MongoEngine
{

	/**
	@var String $host
	*/
	private $host;

	/**
	@var String $user
	*/
	private $user;

	/**
	@var String $pass
	*/
	private $pass;

	/**
	@var String $port
	*/
	private $port;

	/**
	@var String $db
	*/
	private $db;

	/**
	@var MongoClient $client
	*/
	private $client;

	/**
	@var MongoDB $connection
	*/	
	private $connection;


	/**
	@return void
	*/
	public function __construct($host, $user = '', $pass = '', $db, $port = '27017')
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->port = $port;
		$this->db = $db;
	}

	/**
	@return void
	*/
	public function connect()
	{
		/**
		* this method instance a objects MongoClient and select the database
		**/
		$this->client = new MongoClient("mongodb://$this->host:$this->port");
		$this->connection = $this->client->selectDB($this->db);
		$this->authenticate();
	}

	/**
	@return void
	*/
	public function disconnect()
	{
		$this->client->close();
	}

	/**
	@return void
	*/
	public function authenticate()
	{
		if ($this->user != '') {
			$this->connection->authenticate($this->user, $this->pass);
		}
	}

	/**
	@return Array
	*/
	public function listCollections()
	{
		return $this->connection->listCollections();
	}

	/**
	@return Array
	*/
	public function getCollectionNames()
	{
		return $this->connection->getCollectionNames();
	}

	/**
	@return Array
	*/
	public function getCollectionInfo()
	{
		return $this->connection->getCollectionInfo();
	}

	/**
	@return Array or false
	*/
	public function getCollectionData($collection, $filters = false, $sort = false, $limit = false)
	{
		$response = array();
		$filters = $filters==false?[]:$filters;
		$sort = $sort==false?[]:$sort;
		$limit = $limit==false?0:$limit;
		$cursor = $this->connection->$collection->find($filters)->sort($sort)->limit($limit);
		foreach ($cursor as $doc) {
		    array_push($response, $doc);
		}

		if (count($response) > 0) {
			return $response;
		} else {
			return false;
		}
	}

	/**
	@return StamentResult
	*/
	public function query($query)
	{
		return $this->connection->execute($query);
	}

	/**
	@return String
	*/
	public function getID($id_object)
	{
		return $id_object->__toString();
	}
}

?>