<?php 

require_once('autoload.php');

class DeleteData extends Base
{
	/**
	@var String $mysql_host
	*/
	private $mysql_host;

	/**
	@var String $mysql_user
	*/
	private $mysql_user;

	/**
	@var String $mysql_pass
	*/
	private $mysql_pass;

	/**
	@var String $mysql_port
	*/
	private $mysql_port;

	/**
	@var String $mysql_db
	*/
	private $mysql_db;

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
		$this->mysql_host = $this->config['mysql']['host'];
		$this->mysql_user = $this->config['mysql']['user'];
		$this->mysql_pass = $this->config['mysql']['pass'];
		$this->mysql_port = $this->config['mysql']['port'];
		$this->mysql_db = $this->config['mysql']['db'];
		$this->mongo_duplicate_file = $this->config['storage']['duplicate'];
	}

	/**
	@return void
	*/
	public function start()
	{
		if (!file_exists($this->mongo_duplicate_file)) {
			throw new Exception("The file $this->mongo_duplicate_file not exists.");
		}

		$mysql = MysqlEngine::getconnection($this->mysql_host, $this->mysql_user, $this->mysql_pass, $this->mysql_db);

		$duplicate_data = array();
		$file_handle = fopen($this->mongo_duplicate_file, "r");
		while (!feof($file_handle)) {
			$line = trim(fgetss($file_handle));
			if ($line != '') {
		   		array_push($duplicate_data, $line);
			}
		}
		fclose($file_handle);

		$len = count($duplicate_data);
		for ($i=0; $i < $len; $i++) {
			$sql = "select idAvImport, count(idAvImport) as cant, estado, fchRegistro from mod_aviso_aviso_inmueble where idAvImport = $duplicate_data[$i]";
			$result = $mysql->Query($sql);

			if ($result[0]['cant'] > 1) {
				$this->Update($result);
			}

			echo $result[0]['idAvImport'] . "|" . $result[0]['cant'] . PHP_EOL;
		}

		$mysql->Disconnect();
		echo "Data Processed $len result" . PHP_EOL;
	}

	public function Update($result)
	{
		$sql = "update mod_aviso_aviso_inmueble set estado=3 where idAvImport = $result[0]['idAvImport'] and fchRegistro between ('2017-02-01') and ('2017-02-31')";
		$result = $mysql->Query($sql);
		echo "." . PHP_EOL;
	}

}


?>