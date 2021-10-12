<?php
trait GestorBD 
{
	private static $db_host = BD_DATOS['host'];
	private static $db_user = BD_DATOS['usuario'];
	private static $db_pass = BD_DATOS['password'];
	private $configs = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ];
	private $connDB;
	protected $db_name = BD_DATOS['nombreBD'];
	private $query;
	protected $rows = [];
	private $afect_r;
	protected $last_id;
	private $arrayDats;
	private $arrayProcess;
	
	protected function conectarBD(){ 
		try{
			$this->connDB = new PDO("mysql:host=".self::$db_host.";dbname=".$this->db_name,self::$db_user,self::$db_pass,$this->configs);			
		}
		catch(PDOException $err){echo "Error 600: ",  $err->getMessage(), "\n";}
	}
	
	protected function consultaSimple (string $tabla, array $columna, string $condiciones = ''){
		$columna = implode(",", $columna);
		if ($condiciones ==! ''){		
			$this->query = "SELECT $columna FROM $tabla $condiciones";				
		}else{			
			$this->query = "SELECT $columna FROM $tabla";			
		}
		$this->obtener_del_query();		
	}	
	
	protected function sintaxisLibre(string $string){
		$this->query = $string;
		$this->obtener_del_query();
	}

	protected function actualizar(string $tabla, array $valores, array $condicion){
		foreach ($valores as $key => $value) {			
			$this->arrayDats[':'. $key] = $value;
			$columnas[] =  $key.'=:'.$key;
		}
		$columnas = implode(",", $columnas);
		foreach ($condicion as $key => $value) {			
			$this->arrayDats[':'. $key] = $value;
			$condiciones [] =  $key.'=:'.$key;
		}		
		$condiciones = implode(",", $condiciones);
		$this->query = "UPDATE $tabla SET $columnas WHERE $condiciones";				
		$this->ejecucion_simple_query();
		unset($this->arrayDats);		
	}
	
	protected function insertar(string $tabla, array $valores){
		foreach ($valores as $key => $value) {
			$arregloN[]= ':'.$key;
			$this->arrayDats[':'. $key] = $value;
			$columnas [] =  $key;
		}
		$columnas = implode(",", $columnas);
		$arregloN = implode(",", $arregloN);
		$this->query = "INSERT INTO $tabla ($columnas) VALUES ($arregloN)";				
		$this->ejecucion_simple_query();
		$this->last_id = $this->connDB->lastInsertId();
		unset($this->arrayDats);	
	}
	
	protected function borradoSimple(string $tabla, string $condicion){
		$this->query = "DELETE FROM $tabla WHERE $condicion";
		$this->ejecucion_simple_query();
	}	
	
	protected function cierra_conexion() {
		try{
			$this->connDB = null;
		}
		catch(PDOException $err){echo "Error 603: ",  $err->getMessage(), "\n";}		
	}	
	
	protected function ejecucion_simple_query() {
	try {    
    	$this->afect_r = $this->connDB->prepare($this->query);
    	$this->afect_r->execute($this->arrayDats);    	
		} catch(PDOException $err) {echo "Error 601:", $err->getMessage(), "\n";}
	}

	protected function obtener_del_query() {
		$result = $this->connDB->query($this->query);
		while ($this->rows[] = $result->fetch(PDO::FETCH_ASSOC));			
		array_pop($this->rows);
	}
	
	protected function ejecuta_procedimiento(int $incremento,string $adicional = NULL,int $oper = NULL){
		$this->afect_r = $this->connDB->prepare($this->query);
		$this->afect_r->execute($this->arrayProcess[$incremento]);
		if ($adicional) {
			$this->afect_r->closeCursor();
			$result = $this->connDB->query($adicional);
			if ($oper === 1){
				do {
					$this->rows[] = $result->fetchAll(PDO::FETCH_ASSOC);					
				} while ($result->nextRowset());
				array_filter(array_pop($this->rows));				
			}else{
				while ($this->rows[] = $result->fetch(PDO::FETCH_ASSOC));
				array_pop($this->rows);
			}			
		}else{
			if ($oper === 1){
				do {
					$this->rows[] = $this->afect_r->fetchAll(PDO::FETCH_ASSOC);
				} while ($this->afect_r->nextRowset());
				array_filter(array_pop($this->rows));
			}else{
				while ($this->rows[] = $this->afect_r->fetch(PDO::FETCH_ASSOC));
				array_pop($this->rows);
			}			
			$this->afect_r->closeCursor();
		}		
	}
}