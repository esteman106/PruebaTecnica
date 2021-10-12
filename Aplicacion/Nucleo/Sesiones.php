<?php
/*******************************************************************************
 * Sesiones Trait                                                             	*
 *                                                                              *
 * Version: 2.00                                                                *
 * Date:    2018-09-23                                                          *
 * Author:  Manuel Romero                                                       *
 *******************************************************************************/
trait Sesiones{	
	
	public static function Iniciar_Sesion() {
		session_start();		
	}
	
	public static function Configurar_Sesion(){
		$session_name = SESION_NAME;
		$secure = SSL_INIT;
		$httponly = HTTP_ONLY;
		if (ini_set('session.use_only_cookies', 1) === FALSE) {
			exit();
		}
		$cookieParams = session_get_cookie_params();
		session_name($session_name);
		session_set_cookie_params($cookieParams["lifetime"],
				$cookieParams["path"],
				$cookieParams["domain"],
				$secure,
				$httponly);		
	}
	
	public static function Regenerar_Sesion(){
		session_regenerate_id();
	}
	
	public static function Cerrar_Sesion(){
		$params = session_get_cookie_params ();
		setcookie ( session_name (), '', time () - 42000, $params ["path"], $params ["domain"], $params ["secure"], $params ["httponly"] );
		unset($_SESSION);
		session_destroy ();
	}	
	
	public static function Ingresa_Global(string $clave, $valor){		
		if (!empty($clave)){
			$_SESSION[$clave] = $valor;
		}
	}
	
	public static function Obtener_Global(string $clave){
		if (isset($_SESSION[$clave])){
			return $_SESSION[$clave];
		}
	}
	
	public static function Borrar_Global(string $clave){
		if (isset($_SESSION[$clave])) {
			unset($_SESSION[$clave]);
		}
	}	
	
}

