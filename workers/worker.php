<?php 

require_once('autoload.php');

class Worker
{

	/**
	@return void
	*/
	function __construct($command)
	{
		try {
			$command = new $command();
			$command->start();
			exit(0);
		} catch (Exception $e) {
			echo $e;
			exit(0);
		}
	}
}

$worker = new Worker($argv[1]);
?>