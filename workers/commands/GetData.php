<?php
require_once('autoload.php');

class GetData extends Base
{
	
	/**
	@var String $mongo_new
	*/
	private $mongo_new;

	/**
	@var String $mongo_user
	*/
	private $mongo_user;

	/**
	@var String $mongo_pass
	*/
	private $mongo_pass;

	/**
	@var String $mongo_port
	*/
	private $mongo_port;

	/**
	@var String $mongo_db
	*/
	private $mongo_db;

	/**
	@var String $mongo_collection
	*/
	private $mongo_collection;

	/**
	@var Path $mongo_new_file
	*/
	private $mongo_new_file;

	/**
	@return void
	*/
	public function __construct()
	{
		$this->loadConfig();
		$this->mongo_new = $this->config['mongodb']['host_new'];
		$this->mongo_user = $this->config['mongodb']['user_new'];
		$this->mongo_pass = $this->config['mongodb']['pass_new'];
		$this->mongo_port = $this->config['mongodb']['port_new'];
		$this->mongo_db = $this->config['mongodb']['name'];
		$this->mongo_collection = $this->config['mongodb']['collection'];
		$this->mongo_new_file = $this->config['storage']['new'];
	}

	/**
	@return void
	*/
	public function start()
	{
		if (file_exists($this->mongo_new_file)) {
			throw new Exception("The file $this->mongo_new_file exists.");
		}

		$mongo_new = new MongoEngine($this->mongo_new, '', '', $this->mongo_db);
		#$mongo_new = new MongoEngine($this->mongo_new, $this->mongo_user, $this->mongo_pass, $this->mongo_db, $this->mongo_port);
		$mongo_new->connect();

		$stringtime = '2015-08-11T05:00:00.000Z';
		$inputdate = strtotime($stringtime, 1000*intval(substr($stringtime, -4, 3)));
		$isodate = new MongoDate($inputdate);
		echo $isodate;
		$filters = ['fecha' => $isodate]; # array or false
		#$sort = ['fecha' => -1]; # array or false
		$sort = false; # array or false
		$limit = 10; # int or false
		$result = $mongo_new->getCollectionData($this->mongo_collection,$filters, $sort, $limit);
		
		$len = count($result);
		for ($i=0; $i < $len; $i++) { 
			$id = $result[$i]['idInmueble'];
			$this->saveData($this->mongo_new_file, $id);
		}

		$mongo_new->disconnect();

		echo "Data Loaded in $this->mongo_new_file ($len result)" . PHP_EOL;
	}
}

?>