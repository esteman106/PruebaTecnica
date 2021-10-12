<?php
abstract class Controlador
{     
   
   protected $Varwork = [];    
   protected $vistas;
   protected $modelos; 
   protected $error500 ='<h1>ERROR</h1><div class="alert alert-danger">
							<a href="javascript:history.back(1)"><i class="fa fa-arrow-circle-left"></i></a>
					<strong>Proceso no disponible!!</strong>, presione atr&#225;s para regresar a
					la &#250;ltima p&#225;gina vista.</div>';
   protected $errLib = '<a href="javascript:history.back(1)"><i class="fa fa-arrow-circle-left"></i></a>
					<strong>Error de libreria!!</strong>, presione atr&#225;s para regresar a la
					&#250;ltima p&#225;gina vista.';
   protected $errMod = '<a href="javascript:history.back(1)"><i class="fa fa-arrow-circle-left"></i></a>
					<strong>Error de modelo!!</strong>, presione atr&#225;s para regresar a la
					&#250;ltima p&#225;gina vista.';
   protected $errInput = '<a href="javascript:history.back(1)"><i class="fa fa-arrow-circle-left"></i></a>
					<strong>Error, Intento de ataque!!</strong>, se registra; presione atr&#225;s para regresar a la
					&#250;ltima p&#225;gina vista.';
   protected $error403 ='<h1>ERROR</h1><div class="alert alert-danger">
							<a href="javascript:history.back(1)"><i class="fa fa-arrow-circle-left"></i></a>
					<strong>Permisos insufientes en tu perfil!!</strong>, presione atr&#225;s para regresar a
					la &#250;ltima p&#225;gina vista.</div>';
    
   public function __construct(){   		
   		$this->vistas = new Vistas;
         $this->cargaModelo ( 'Index' );   		
   }
   
   abstract public function Index(); 
   
   protected function cargaModelo(string $modelo){
   		$modelo = $modelo . 'Modelo';
   		$rutaModelo = APP_DIR . 'Modelos' . SD . $modelo . '.php';   
   		if(is_readable($rutaModelo)){
   			require_once $rutaModelo;
   			$this->modelos = new $modelo;   		
   		}
   		else {
   			throw new Exception($this->errMod);
   		}
   }
   
   protected function cargaLibreria(string $libreria){
   	$rutaLibreria = APP_DIR . 'Libs' . SD . $libreria . '.php';   
   		if(is_readable($rutaLibreria)){
   			require_once $rutaLibreria;
   		}
   		else{
   			throw new Exception($this->errLib);
   		}
   }
   
   protected function Redireccionar(string $ruta = NULL){
   	if($ruta){
   		header('location:' . BASE_URL . $ruta);
   		exit;
   	}
   	else{
   		header('location:' . BASE_URL);
   		exit;
   	}
   }
   
   protected function FiltraGlobales(array $argumentos,int $tipo = INPUT_POST){ 
   		$entradasPost = filter_input_array($tipo, $argumentos);
   		if($entradasPost and is_array($entradasPost)){
        	$this->Varwork = $this->Varwork + $entradasPost;            
            unset($_POST);
            return 103;
        }else{
        	throw new Exception($this->errInput);
        }        
    }    
    
   /* Cargador de archivos en formularios */
    
    protected function ProcesaArchivo(array $params){    	
    	if (isset($_FILES[$params['nombre']]) and is_uploaded_file($_FILES[$params['nombre']]['tmp_name'])) {
    		$tmp_name = $_FILES[$params['nombre']]['tmp_name'];
    		$nombre = $_FILES[$params['nombre']]['name'];
    		$nombre = str_replace(" ","",$nombre);
    		$extension = explode(".", strtolower($nombre));
    		if (in_array($extension[1], $params['extensiones']))
    		{
    			move_uploaded_file($tmp_name, $params['ubicacion'] . $nombre);
    			return $nombre;
    		}else{
    			return 103;// Extension desconocida
    		}
    	}else{
    		return 102; // Archivo no cargado
    	}
    }    
   
    /* Codificadores de URL y decodificador */
    
    protected function Codificador_Url(string $caracteres, string $fcontrol = DATA_KEY):string{
    	$caracteres= \utf8_encode($caracteres);    	
    	$caracteres = $fcontrol.$caracteres.$fcontrol;
    	$caracteres = base64_encode($caracteres);
    	return $caracteres;
    }
    
    protected function Decodificador_Noget(string $caracteres, string $fcontrol = DATA_KEY):string{
    	$caracteres = base64_decode($caracteres);
    	$fcontrol= DATA_KEY;
    	$caracteres = str_replace($fcontrol, "", "$caracteres");
    	$caracteres = utf8_decode($caracteres);
    	return $caracteres;
    }   
   
    /* Crea un listado para select */
    protected function creaListaSelec(array $datadb,string $dat1,string $dat2, string $seleccion = null):string{ 
    	$datafinal = "";
    	for ($i = 0; $i < count($datadb); $i++){
    		if(!empty($seleccion) and $datadb[$i][$dat1] === $seleccion){
    			$datafinal .= '<option value="'.$datadb[$i][$dat1].'" selected>'.$datadb[$i][$dat2].'</option>';    			
    		}else
    		$datafinal .= '<option value="'.$datadb[$i][$dat1].'">'.$datadb[$i][$dat2].'</option>';
    	}    	
    	return $datafinal;
    }
    /* Crea un listado para Datalist */
    protected function creaDatalist(array $infodb,string $valores,string $etiquetas):string{
    	$datafinal = "";
    	for ($i = 0; $i < count($infodb); $i++){
    		$datafinal .= '<option value="'.$infodb[$i][$valores].'" label="'.$infodb[$i][$etiquetas].'">';
    	}
    	return $datafinal;
    }
    
    protected function listadoJSON(array $infodb,string $dat1,string $dat2):array{ // Generacion de listados JSON
    	$datafinal = [];
    	for ($i = 0; $i < count($infodb); $i++){
    		$datafinal[$infodb[$i][$dat1]] = $infodb[$i][$dat2];
    	}    	
    	return $datafinal;
    } 
    
    
}