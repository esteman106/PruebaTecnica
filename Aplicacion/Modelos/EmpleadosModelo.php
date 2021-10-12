<?php
class EmpleadosModelo extends Modelos {
	public function __construct() {
		parent::__construct();				
	}
	
	public function ConsultaRoles()
	{
		$this->ConsultaSimple('roles',['id','nombre']);
		$data = parent::ValidarConsultaDB();
		return $data;
	}

	public function ConsultaAreas()
	{
		$this->ConsultaSimple('areas',['id','nombre']);
		$data = parent::ValidarConsultaDB();
		return $data;
	}

	public function CrearEmpleado(array $data)
	{
	    $sexo ='';
	    $boletin = '';
	    if ($data['sexo'][0]) 
	        $sexo = 'M';
	        else  $sexo = 'F';
	     if ($data['boletines']) $boletin = '1';
	     else $boletin = '0';        
	   
	    
		$this->insertar('empleados',['nombre'=>$data['nombre'],'email'=>$data['email'],'sexo'=>$sexo,'descripcion'=>$data['descripcion'],
		    'area_id'=>$data['areas'],'boletin'=>$boletin]);

		$id = $this->last_id;

		if (!empty($data['roles'])) {
			foreach ($data['roles'] as $value) {
 		    	$this->insertar('empleado_rol',['empleado_id'=>$id,'rol_id'=>$value]);
 			}
 		}

		parent::Ingresa_Global('tipo','alert-success');
 		parent::Ingresa_Global('mensaje','<strong>Empleado Creado!</strong> su ID es '.$id);
		
	}
	
	public function TraerEmpleado(string $id)
	{
	    $data = $this->consultaSimple("empleados", ['id','nombre','email','sexo','area_id','boletin','descripcion'],"WHERE id = '".$id."' LIMIT 1");
	    $data = $this->rows[0];
	    unset($this->rows);
	    return $data;
	}
	
	public function ConsultaRolesbyEmpleado(string $id){
	    $this->consultaSimple("empleado_rol", ['rol_id'],"WHERE empleado_id = '".$id."'");
	    $data = parent::ValidarConsultaDB();
	    return $data;
	}

	public function confirmarRol(string $id_empleado, string $id_rol){
		$this->consultaSimple("empleado_rol", ['rol_id'],"WHERE empleado_id = '".$id_empleado."' and rol_id = '".$id_rol."'");
		if($this->rows){
			unset($this->rows);
			return true;
		}
	}
	
	public function ActualizarEmpleado(array $data, string $id)
	{
	    $sexo ='';
	    $boletin = '';

	    if ($data['sexo'][0]) $sexo = 'M';
	     else  $sexo = 'F';
	    
	    if ($data['boletines']) $boletin = '1';
	    else $boletin = '0';
	        
	        
	    $this->actualizar('empleados',['nombre'=>$data['nombre'],'email'=>$data['email'],'sexo'=>$sexo,'descripcion'=>$data['descripcion'],
	            'area_id'=>$data['areas'],'boletin'=>$boletin],['id' => $id]); 

	    $datadb = self::ConsultaRolesbyEmpleado($id);

	    if (!empty($data['roles'])) {
	       if (!empty($datadb)) {
	       		for ($i=0; $i < count($datadb) ; $i++) { 
	       			if(in_array($datadb[$i]['rol_id'], $data['roles'])){
	       				$clave = array_search($datadb[$i]['rol_id'], $data['roles']);
	       				unset($data['roles'][$clave]);
	       			}else{
	       				$this->borradoSimple("empleado_rol","empleado_id = '".$id."' and rol_id = '".$datadb[$i]['rol_id']."'");
	       			}
	       		}
	       		
	       		foreach ($data['roles'] as $value) {
 		    		$this->insertar('empleado_rol',['empleado_id'=>$id,'rol_id'=>$value]);
 				}

	       }else{
	       		
	       		foreach ($data['roles'] as $value) {
 		    		$this->insertar('empleado_rol',['empleado_id'=>$id,'rol_id'=>$value]);
 				}
	       }


	    }else{
	    	 $this->borradoSimple("empleado_rol","empleado_id = '".$id."'");
	    }     
	    
	    parent::Ingresa_Global('tipo','alert-success');
 		parent::Ingresa_Global('mensaje','<strong>Empleado actualizado correctamente!</strong>');
	   
	}

	
	
	public function EliminarEmpleado(string $id)
	{
	    $this->borradoSimple("empleados","id = '".$id."' LIMIT 1");
	    $this->borradoSimple("empleado_rol","empleado_id = '".$id."'");

	    parent::Ingresa_Global('tipo','alert-warning');
 		parent::Ingresa_Global('mensaje','<strong>Registro de empleado eliminado!</strong>');
	}
}