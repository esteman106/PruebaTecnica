<?php
abstract class Modelos
{
	use GestorBD,Sesiones;
	
	public function __construct(){	
		$this->conectarBD();
	}
	
	protected function stringAleatorio(int $longitud):string{
		$cadena = "";
		$caracteres = "1234567890abcdefghijklmnopqrstuvwxyzABCDFGHIJKLMNOPQRSTWVXYZ";
		for($i = 0; $i < $longitud; $i++){
			$cadena  .= $caracteres[mt_rand(0, 59)];
		}
		return $cadena;
	}
	
	protected function ValidarConsultaDB():array{
		if ($this->rows) {
			$datosbd = $this->rows;
			unset($this->rows);
		}else{
			$datosbd=[];
		}
		return $datosbd;
	}
	
	public function ConsultaEmpleados()
	{
	    $this->sintaxisLibre("SELECT empleados.id,empleados.nombre,empleados.email,(SELECT IF(STRCMP(empleados.sexo,'M') = 0, 'Masculino', 'Femenino')) 
            AS genero, areas.id AS id_area, areas.nombre AS n_area,(SELECT IF(empleados.boletin = 0, 'No', 'Si')) 
            AS boletines, empleados.descripcion FROM empleados INNER JOIN areas ON empleados.area_id = areas.id;");
	    $databd = self::ValidarConsultaDB();
	    return $databd;
	}
	
	public function RegistroPost(string $clase){
		$texto = 'Clase: '.$clase.' desde la IP -> '.$_SERVER["REMOTE_ADDR"];
		$this->insertar('logsbd', ['Id_Proceso'=>107,'Info_Forms'=>$texto]);
	}	
}