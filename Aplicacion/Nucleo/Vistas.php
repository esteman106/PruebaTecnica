<?php
class Vistas{
	
	public $VarHtml = [];	
	private $rendermenu = ['home'=>PUBLIC_HTML,'url'=>BASE_URL];	
	private $errView = '<h1>ERROR</h1><div class="alert alert-danger">
							<a href="javascript:history.back(1)"><i class="fa fa-arrow-circle-left"></i></a>
					<strong>Segmento no disponible!!</strong>, presione atr&#225;s para regresar a
					la &#250;ltima p&#225;gina vista.</div>';
	
	public function ubicarHTML(string $vista = NULL, string $template = NULL,int $oper = 1){
		$rutaView = PUBLIC_DIR . $vista . EXT_CONTENT;
		if(is_readable($rutaView) and $oper === 1){
			$this->cuerpoAdicional = self::renderizado(self::obtenerContenido($rutaView));
			if ($template === 'Dash') $this->menuSer = self::renderizado_menu($this->menuSer); //Ingreso del Menu
			require_once PUBLIC_DIR . 'Template'. $template .'.php';
		}elseif (is_readable($rutaView) and $oper === 2){
			$htmldata = $this->renderizado($this->obtenerContenido($rutaView));
			return $htmldata;
		}else {
			throw new Exception($this->errView);
		}
	}
	
	public function modalHTML(string $modal = NULL){
		$rutaView = PUBLIC_DIR . $modal. EXT_CONTENT;
		if(is_readable($rutaView)){
			$this->modalsIncluidas = self::renderizado(self::obtenerContenido($rutaView));			
		}else {
			$this->modalsIncluidas = '<h1>ERROR</h1><div class="alert alert-danger">
							</a><strong>Modal no disponible!!</strong>, contacte administrador!!</div>';			
		}
	}
	
	private function obtenerContenido(string $Url):string{
		$htmlTemp = mb_convert_encoding(file_get_contents($Url),FORMAT_CONTENT);
		return $htmlTemp;
	}
	
	private function renderizado(string $html):string{
	    foreach ($this->VarHtml as $clave => $valor) {
	        if(is_array($valor)){
	            foreach ($valor as $key => $string) {
	                $html = \str_replace('{'.$key.'}', $string, $html);
	            }
	        }else{
	            $html = \str_replace('{'.$clave.'}', $valor, $html);
	        }
	    }
	    return $html;
	}
	
	private function renderizado_menu(string &$html = NULL){
		foreach($this->rendermenu as $clave => $valor){
			$html = str_replace('{'.$clave.'}', $valor, $html);
		}
		return $html;
	}		
}