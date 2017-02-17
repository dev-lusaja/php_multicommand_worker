<?php 

require_once('autoload.php');

class CompareData extends Base
{
	/**
	@var String $mongo_old
	*/
	private $mongo_old;

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
	@var Path $mongo_duplicate_file
	*/
	private $mongo_duplicate_file;

	/**
	@return void
	*/
	public function __construct()
	{
		$this->loadConfig();
		$this->mongo_old = $this->config['mongodb']['host_old'];
		$this->mongo_db = $this->config['mongodb']['name'];
		$this->mongo_collection = $this->config['mongodb']['collection'];
		$this->mongo_new_file = $this->config['storage']['new'];
		$this->mongo_duplicate_file = $this->config['storage']['duplicate'];
	}

	/**
	@return void
	*/
	public function start()
	{
		if (!file_exists($this->mongo_new_file)) {
			throw new Exception("The file $this->mongo_new_file not exists.");
		}

		if (file_exists($this->mongo_duplicate_file)) {
			throw new Exception("The file $this->mongo_duplicate_file exists.");
		}

		$mongo_old = new MongoEngine($this->mongo_old, '', '', $this->mongo_db);
		$mongo_old->connect();

		$mongo_new_id = array();
		$file_handle = fopen($this->mongo_new_file, "r");
		while (!feof($file_handle)) {
			$line = trim(fgetss($file_handle));
			if ($line != '') {
		   		array_push($mongo_new_id, $line);
			}
		}
		fclose($file_handle);

		$len = count($mongo_new_id);
		$duplicates = 0;
		for ($i=0; $i < $len; $i++) { 
			$filters = ['idInmueble' => $mongo_new_id[$i]];
			$result = $mongo_old->getCollectionData($this->mongo_collection, $filters);
			if ($result) {
				$this->saveData($this->mongo_duplicate_file, $result[0]['idInmueble']);
				$duplicates++;
			}
		}

		$mongo_old->disconnect();

		echo "Data Loaded in $this->mongo_duplicate_file ($duplicates result)" . PHP_EOL;
	}
}

?>