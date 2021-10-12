<?php
error_reporting(0);
require_once('Globales.php');

try {
 	spl_autoload_register(function($clase){
 		if(file_exists(APP_NUCLEO.$clase.".php")){
 			require_once APP_NUCLEO.$clase.".php";
        }
    });    
	$app = new Routes();
}catch (Exception $err){
	include_once PUBLIC_DIR . 'templateError.php';	
}