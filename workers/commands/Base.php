<?php

class Base
{
	public $config;

	public function __construct()
	{
		# code...
	}

	public function loadConfig()
	{
		$this->config = parse_ini_file("configs/config.ini", true);
		return $this->config;
	}

	public function saveData($file, $data)
	{
		file_put_contents($file, $data . "\n", FILE_APPEND);
	}

	public function start()
	{
		throw new Exception("No method implemented.");
		
	}
}

?>