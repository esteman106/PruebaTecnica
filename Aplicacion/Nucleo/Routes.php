<?php
class Routes {
	
	protected $controlador ='Index';
	protected $metodo;
	protected $parametros= [];
	
	public function __construct() {
		$servidor = PROTOCOLO . $_SERVER['SERVER_NAME'];		
		if($servidor === DOMAIN_URL){
			$url = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
			//$get_vars = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY));
			if (BASE_INDEX !== "/") {
				$url = explode("/", str_replace(BASE_INDEX, "", $url));	
				if(isset($url[0]) and !empty($url[0])){
					$this->controlador = ucfirst(array_shift($url));
					$this->metodo = ucfirst(str_replace("-","",array_shift($url)));
					$this->parametros = $url;
				}
			}else{
				$url = array_filter(explode("/", $url));
				if(isset($url[1]) and !empty($url[1])){
					$this->controlador = ucfirst(array_shift($url));
					$this->metodo = ucfirst(str_replace("-","",array_shift($url)));
					$this->parametros = $url;
				}
			}			
			if (file_exists(APP_DIR . 'Controladores' . SD . $this->controlador . 'Controlador.php')) {					
				require_once APP_DIR . 'Controladores' . SD . $this->controlador . 'Controlador.php';
				$this->controlador = new $this->controlador();
				if (method_exists($this->controlador, $this->metodo)) {
					$this->metodo = $this->metodo;
				}else{
					$this->metodo = 'Index';
				}
				
				$this->parametros = $url ? array_values($url) : [];											
					
				call_user_func_array([$this->controlador, $this->metodo], str_replace('-','_',$this->parametros));
					
			}else{
				$string = '<a href="javascript:history.back(1)"><i class="fa fa-arrow-circle-left"></i></a>
					<strong>URL Desconocida</strong>, presione atr&#225;s para regresar a la &#250;ltima p&#225;gina vista.';
				throw new Exception($string);
			}
		}else{
			$string = '<strong>Error de configuracion!!</strong>, contacte administrador, presione  
					<a href="mailto:'. TEST_EMAIL .'">aqui</a>.</div>';
			throw new Exception($string);
		}			
	}	
}