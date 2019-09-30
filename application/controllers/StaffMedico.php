<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StaffMedico extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','imagen_helper','otros_helper','fechas_helper'));
		$this->load->model(array('model_staff_medico'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->sessionFactur = @$this->session->userdata('sess_fact_'.substr(base_url(),-20,7));
		date_default_timezone_set("America/Lima");
		//if(!@$this->user) redirect ('inicio/login');
		//$permisos = cargar_permisos_del_usuario($this->user->idusuario); 
	}
	public function listar_staff()
	{ 
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];
		$lista = $this->model_staff_medico->m_cargar_staff($paramPaginate);
		$fCount = $this->model_staff_medico->m_count_staff($paramPaginate);
		$arrListado = array();
		foreach ($lista as $row) { 
			$strDescripcion = 'OCULTO';
			$strClaseLabel = ' label-default';
			if($row['visible'] === '1'){
				$strDescripcion = 'VISIBLE';
				$strClaseLabel = ' label-success';
			}
			array_push($arrListado,
				array(
					'idmedico' => $row['idmedico'],
					'nombres' => $row['nombres'],
					'ap_paterno' => $row['ap_paterno'],
					'ap_materno' => $row['ap_materno'],
					'sexo' => $row['sexo'],
					'cmp' => $row['cmp'],
					'rne' => $row['rne'],
					'lema' => $row['lema'],
					'estudios_html' => $row['estudios_html'],
					'foto' => $row['foto'],
					'foto_perfil' => $row['foto_perfil'],
					'visible' => $row['visible'],
					'estado' => array(
						'claseLabel' => $strClaseLabel,
						'visible' => $row['visible'],
						'labelText'=> $strDescripcion
					)
				)
			);
		}
    	$arrData['datos'] = $arrListado;
    	$arrData['paginate']['totalRows'] = $fCount['contador'];
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function ver_popup_formulario()
	{
		$this->load->view('staff-medico/mant_staffMedico');
	}
	public function ver_popup_horarios()
	{
		$this->load->view('staff-medico/mant_horarioMedico');
	}
	public function ver_popup_especialidades()
	{
		$this->load->view('staff-medico/mant_especialidad');
	}
	public function registrar()
	{
		// $allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$allInputs = array();
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// var_dump($_SERVER['DOCUMENT_ROOT'], 'docroot'); exit();
    	// VALIDACIONES 
	    /* VALIDAR SI EL CMP YA EXISTE */ 
	    // var_dump($this->input->post(), 'cmp'); exit();
	    $allInputs['cmp'] = $this->input->post('cmp');
    	$fMedico = $this->model_staff_medico->m_validar_cmp($allInputs['cmp']);
    	if( !empty($fMedico) ) {
    		$arrData['message'] = 'El CMP ingresado, ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
    	$allInputs['nombres'] = $this->input->post('nombres');
    	$allInputs['ap_paterno'] = $this->input->post('ap_paterno');
    	$allInputs['ap_materno'] = $this->input->post('ap_materno');
    	$allInputs['sexo'] = $this->input->post('sexo');
    	$allInputs['rne'] = $this->input->post('rne');
    	$allInputs['lema'] = $this->input->post('lema');
    	$allInputs['estudios_html'] = $this->input->post('estudios_html');
    	$allInputs['visible'] = $this->input->post('visible');
    	$allInputs['foto'] = $allInputs['sexo'] === 'F' ? 'AVATAR-M.png' : 'AVATAR-H.png';
    	$allInputs['foto_perfil'] = $allInputs['sexo'] === 'F' ? 'AVATAR-M.png' : 'AVATAR-H.png';
    	$this->db->trans_start();
		if( !empty($_FILES['foto_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['foto_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['cmp'].'_tb.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/medico','foto_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['foto'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['foto_perfil_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['foto_perfil_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['cmp'].'_perfil.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/medico/medico-perfil','foto_perfil_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['foto_perfil'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		// registro de medico
		// var_dump($allInputs); exit();
		if($this->model_staff_medico->m_registrar($allInputs)){
			$arrData['message'] = 'Se registraron los datos correctamente';
			$arrData['flag'] = 1;
		}
		$this->db->trans_complete();
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function editar()
	{
		$allInputs = array();
		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// VALIDACIONES
		/* VALIDAR SI EL RUC YA EXISTE */
    	$allInputs['cmp'] = $this->input->post('cmp');
    	$allInputs['idmedico'] = $this->input->post('idmedico');
    	$fMedico = $this->model_staff_medico->m_validar_cmp_edit($allInputs['cmp'],$allInputs['idmedico']);
    	if( !empty($fMedico) ) {
    		$arrData['message'] = 'El CMP ingresado, ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$allInputs['nombres'] = $this->input->post('nombres');
    	$allInputs['ap_paterno'] = $this->input->post('ap_paterno');
    	$allInputs['ap_materno'] = $this->input->post('ap_materno');
    	$allInputs['sexo'] = $this->input->post('sexo');
    	$allInputs['rne'] = $this->input->post('rne');
    	$allInputs['lema'] = $this->input->post('lema');
    	$allInputs['estudios_html'] = $this->input->post('estudios_html');
    	$allInputs['visible'] = $this->input->post('visible');
    	// $allInputs['foto'] = $allInputs['sexo'] === 'F' ? 'AVATAR-M.png' : 'AVATAR-H.png';
    	// $allInputs['foto_perfil'] = $allInputs['sexo'] === 'F' ? 'AVATAR-M.png' : 'AVATAR-H.png';

    	$this->db->trans_start();
		if( !empty($_FILES['foto_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['foto_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['cmp'].'_tb.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/medico','foto_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['foto'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['foto_perfil_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['foto_perfil_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['cmp'].'_perfil.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/medico/medico-perfil','foto_perfil_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['foto_perfil'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		// registro de medico
		// var_dump($allInputs); exit();
		if($this->model_staff_medico->m_editar($allInputs)){
			$arrData['message'] = 'Se editaron los datos correctamente';
			$arrData['flag'] = 1;
		}
		$this->db->trans_complete();

		// if($this->model_staff_medico->m_editar($allInputs)){
		// 	$arrData['message'] = 'Se editaron los datos correctamente';
		// 	$arrData['flag'] = 1;
		// }
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function eliminar()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$arrData['message'] = 'No se pudo eliminar los datos';
    	$arrData['flag'] = 0;
		if( $this->model_staff_medico->m_eliminar($allInputs) ){ 
			$arrData['message'] = 'Se eliminó el médico correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function ocultar()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$arrData['message'] = 'No se pudo ocultar los datos';
    	$arrData['flag'] = 0;
		if( $this->model_staff_medico->m_ocultar($allInputs) ){ 
			$arrData['message'] = 'Se ocultó el médico correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function mostrar()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$arrData['message'] = 'No se pudo mostrar los datos';
    	$arrData['flag'] = 0;
		if( $this->model_staff_medico->m_mostrar($allInputs) ){ 
			$arrData['message'] = 'Se mostró el médico correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function listar_staff_cbo(){
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$lista = $this->model_staff_medico->m_cargar_staff_cbo();
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado,
				array(
					'id' => $row['idclienteempresa'],
					'descripcion' => strtoupper($row['nombre_comercial']) 
				)
			);
		}

    	$arrData['datos'] = $arrListado;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function listar_staff_autocomplete()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$allInputs['limite'] = 15;
		$lista = $this->model_staff_medico->m_cargar_staff_limite($allInputs);
		$hayStock = true;
		$arrListado = array();

		foreach ($lista as $row) { 
			array_push($arrListado,
				array(
					'id' => $row['idclienteempresa'],
					'nombre_comercial' => strtoupper($row['nombre_comercial'])
				)
			);
		}
		
    	$arrData['datos'] = $arrListado;
    	$arrData['message'] = '';
    	$arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData)); 
	}
}