<?php
class Empleados extends Controlador
{
	public function __construct() {
		parent::__construct();
		$this->cargaModelo("Empleados");		
	}
	
	public function Index(){
		$this->vistas->titulo = 'Crear Empleado';	
		$this->vistas->VarHtml = ['home'=> PUBLIC_HTML,'url'=>BASE_URL,'roles'=>self::ListarRoles(),'areas'=>self::ListarAreas()];	
		$this->vistas->ubicarHTML('NuevoEmpleado');						
	}

	public function Crear(){
		parent::FiltraGlobales(['nombre'=>FILTER_SANITIZE_SPECIAL_CHARS,
				'email'=>FILTER_SANITIZE_SPECIAL_CHARS,'sexo'=>['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
				'flags'  => FILTER_REQUIRE_ARRAY],'areas'=>FILTER_SANITIZE_SPECIAL_CHARS,'descripcion'=>FILTER_SANITIZE_SPECIAL_CHARS,
				'boletines'=>FILTER_SANITIZE_SPECIAL_CHARS,'roles'=>['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
				'flags'  => FILTER_REQUIRE_ARRAY]]);
		$this->modelos->CrearEmpleado($this->Varwork);		
 		$this->Redireccionar();		
	}
	
	public function Editar(string $id){
	    $this->vistas->titulo = 'Editar Empleado';
	    $data = $this->modelos->TraerEmpleado($id);
	    $this->vistas->VarHtml = ['home'=> PUBLIC_HTML,'url'=>BASE_URL,'genero_segmento'=>self::GeneroRadio($data['sexo']),
	        'areas'=>self::ListarAreas($data['area_id']),'boletin_check'=>self::BoletinCheck($data['boletin']),
	        'roles'=>self::ListarRoles($data['id']),$data];	
	    $this->vistas->ubicarHTML('EditarEmpleado');
	}
	
	public function Actualizar(string $id){
	    parent::FiltraGlobales(['nombre'=>FILTER_SANITIZE_SPECIAL_CHARS,
	        'email'=>FILTER_SANITIZE_SPECIAL_CHARS,'sexo'=>['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
	            'flags'  => FILTER_REQUIRE_ARRAY],'areas'=>FILTER_SANITIZE_SPECIAL_CHARS,'descripcion'=>FILTER_SANITIZE_SPECIAL_CHARS,
	        'boletines'=>FILTER_SANITIZE_SPECIAL_CHARS,'roles'=>['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
	            'flags'  => FILTER_REQUIRE_ARRAY]]);	    
	    $this->modelos->ActualizarEmpleado($this->Varwork,$id);    
	    $this->Redireccionar();
	}
	
	public function Eliminar(string $id){
	    $this->modelos->EliminarEmpleado($id);
	    $this->Redireccionar();	
	}

	private function ListarRoles(string $id_emp = null){
		$html = '';
		$data = $this->modelos->ConsultaRoles();
		if (!empty($id_emp)) {		    
		   for ($i=0; $i < count($data) ; $i++) {
		       if($this->modelos->confirmarRol($id_emp,$data[$i]['id'])){
		       		$html .= '<div class="form-check"><input class="form-check-input" type="checkbox" name="roles[]"
                    value="'.$data[$i]['id'].'" id="flexCheckDefault" checked="">
    						<label class="form-check-label" for="flexCheckDefault"> '.$data[$i]['nombre'].'
                              </label></div>';
		       }	        			
		        else{
		        	 $html .= '<div class="form-check"><input class="form-check-input" type="checkbox" name="roles[]"
                    value="'.$data[$i]['id'].'" id="flexCheckDefault">
    				<label class="form-check-label" for="flexCheckDefault"> '.$data[$i]['nombre'].'
                              </label></div>';
		        }      
		              
		    }	    

		}else{
    		  for ($i=0; $i < count($data) ; $i++) { 
    			$html .= '<div class="form-check"><input class="form-check-input" type="checkbox" name="roles[]" value="'.$data[$i]['id'].'" id="flexCheckDefault">
    				<label class="form-check-label" for="flexCheckDefault"> '.$data[$i]['nombre'].'
                              </label></div>';
    		  }
		}
		return $html;
	}

	private function ListarAreas(string $idarea = NULL){
		$html = '';
		$data = $this->modelos->ConsultaAreas();
		$html = $this->creaListaSelec($data,'id','nombre',$idarea);
    	return $html;
	}
	
	private function BoletinCheck(string $var) {
	    $html = '';
	    if ($var) 
	        $html = 'checked=""';
	        return $html;
	}
	
	private function GeneroRadio(string $var) {
	    $html = '';
	    if ($var === "M")
	        $html = '<div class="custom-control custom-radio">
                          <input type="radio" id="customRadio1" name="sexo[]" class="custom-control-input" checked="">
                            <label class="custom-control-label" for="customRadio1">Masculino</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input type="radio" id="customRadio1" name="sexo[]" class="custom-control-input">
                            <label class="custom-control-label" for="customRadio1">Femenino</label>
                        </div>';
	        else $html = '<div class="custom-control custom-radio">
                          <input type="radio" id="customRadio1" name="sexo[]" class="custom-control-input">
                            <label class="custom-control-label" for="customRadio1">Masculino</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input type="radio" id="customRadio1" name="sexo[]" class="custom-control-input" checked="">
                            <label class="custom-control-label" for="customRadio1">Femenino</label>
                        </div>';
	        return $html;
	}
	
}