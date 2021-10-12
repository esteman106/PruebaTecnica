<?php
class Index extends Controlador
{
	public function __construct() {
		parent::__construct();
		parent::cargaModelo("Index");
	}
	
	public function Index(){
			$this->vistas->titulo = 'Listado Empleados';
			$this->vistas->VarHtml = ['home'=> PUBLIC_HTML,'url'=>BASE_URL,'mensajes'=>self::NotificacionesProcesos(),'ListaEmpleados'=>self::ListadoEmpleados()];
			$this->vistas->ubicarHTML('Inicio');					
	}
	
	private function ListadoEmpleados():string 
	{
	    $html = '';
	    $data = $this->modelos->ConsultaEmpleados();
	    if (!empty($data)) {
	        for ($i = 0; $i < count($data); $i++) {
	            $html.= '<tr><td>'.$data[$i]['nombre'].'</td><td>'.$data[$i]['email'].'</td><td>'.$data[$i]['genero'].'</td>
                <td>'.$data[$i]['n_area'].'</td><td>'.$data[$i]['boletines'].'</td>
                <td><a href="'.BASE_URL.'empleados/editar/'.$data[$i]['id'].'"><i class="fa fa-edit"></i></a></td>
                <td><a href="'.BASE_URL.'empleados/eliminar/'.$data[$i]['id'].'"><i class="fa fa-trash"></i></a></td></tr>';
	        }
	    }else $html = '<tr><td colspan="7">Sin empleados registrados</td></tr>';
	    return $html;
	}
	
	private function NotificacionesProcesos() {     	
      $htmlstring = '';
      if($this->modelos->Obtener_Global ( 'tipo' ) and $this->modelos->Obtener_Global ( 'mensaje' )){
         $htmlstring = '<div class="alert '.$this->modelos->Obtener_Global ( 'tipo' ).' alert-dismissible fade show" role="alert">
          '.$this->modelos->Obtener_Global ( 'mensaje' ).'
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
         </div>  ';  
      }     
      $this->modelos->Cerrar_Sesion();       	    
    	return $htmlstring;
    }
}