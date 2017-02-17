<?php 
function __autoload($cn)
{	
	if(strtolower(substr($cn, -6))=='engine'){
		require_once ("engines/$cn.php");
		return;
	}

	require_once ("commands/$cn.php");
	return;
}
spl_autoload_extensions('.php');
spl_autoload_register('__autoload');
?>