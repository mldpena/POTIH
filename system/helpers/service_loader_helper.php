<?php

/**
 * Include a class in a file.
 * @param $className
 */
function __AutoLoader($className)
{
	if(stripos($className, 'CI') === FALSE && stripos($className, 'PEAR') === FALSE) 
	{
		$file = str_replace('\\' ,DIRECTORY_SEPARATOR, $className);
		require_once APPPATH.$file.'.php';
	}
}

spl_autoload_register('__AutoLoader');
