<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Especialidad extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        $this->sessionFactur = @$this->session->userdata('sess_fact_'.substr(base_url(),-20,7));
        // $this->load->helper(array('fechas','otros')); 
        $this->load->helper(array('imagen_helper','otros_helper','fechas_helper'));
        $this->load->model(array('model_especialidad')); 
    }

	public function listar(){ 
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];
		$lista = $this->model_especialidad->m_cargar_especialidad($paramPaginate);
		$fCount = $this->model_especialidad->m_count_especialidad($paramPaginate);
		$arrListado = array();
		foreach ($lista as $row) { 
			$strDescripcion = 'OCULTO';
			$strClaseLabel = ' label-default';
			if($row['visible'] === '1'){
				$strDescripcion = 'VISIBLE';
				$strClaseLabel = ' label-success';
			}
			$strDescripcionHome = 'OCULTO';
			$strClaseLabelHome = ' label-default';
			if($row['visible_home'] === '1'){
				$strDescripcionHome = 'VISIBLE';
				$strClaseLabelHome = ' label-success';
			}
			array_push($arrListado,
				array(
					'idespecialidad' => $row['idespecialidad'],
					'nombre' => $row['nombre'],
					'descripcion_html' => $row['descripcion_html'],
					'titulo_seo' => $row['titulo_seo'],
					'meta_content_seo' => $row['meta_content_seo'],
					'uri' => $row['uri'],
					'image_banner' => $row['image_banner'],
					'icono_home' => $row['icono_home'],
					'visible' => (int)$row['visible'],
					'visible_home' => (int)$row['visible_home'],
					'reserva_cita' => (int)$row['reserva_cita'],
					'tiene_landing' => $row['tiene_landing'],
					'visible_obj' => array(
						'claseLabel' => $strClaseLabel,
						'visible' => $row['visible'],
						'labelText'=> $strDescripcion
					),
					'visible_home_obj' => array(
						'claseLabel' => $strClaseLabelHome,
						'visible' => $row['visible_home'],
						'labelText'=> $strDescripcionHome
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
	public function listarEspMedicos()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];
		$paramDatos = $allInputs['datos'];
		$lista = $this->model_especialidad->m_cargar_especialidades_medico($paramPaginate,$paramDatos);
		$fCount = $this->model_especialidad->m_count_especialidades_medico($paramPaginate,$paramDatos);
		$arrListado = array();
		foreach ($lista as $row) { 
			array_push($arrListado,
				array(
					'idespecialidadmedico' => $row['idespecialidadmedico'],
					'idespecialidad' => $row['idespecialidad'],
					'idmedico' => $row['idmedico'],
					'nombre' => $row['nombre'],
					'uri' => $row['uri']
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
	public function listar_cbo()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$lista = $this->model_especialidad->m_cargar_cbo();
		$arrListado = array();
		foreach ($lista as $row) {
			array_push($arrListado,
				array(
					'id' => $row['idespecialidad'],
					'descripcion' => strtoupper($row['nombre'])
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
	public function ver_popup_formulario()
	{
		$this->load->view('especialidad/mant_especialidad');
	}
	public function registrar()
	{
		// $allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$allInputs = array();
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// VALIDACIONES
    	$allInputs['nombre'] = $this->input->post('nombre');
    	$allInputs['uri'] = $this->input->post('uri');
    	$fEspecialidad = $this->model_especialidad->m_validar_especialidad($allInputs['nombre']);
    	if( !empty($fEspecialidad) ) {
    		$arrData['message'] = 'La especialidad ingresada ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$fEspecialidadUri = $this->model_especialidad->m_validar_especialidad_uri($allInputs['uri']);
    	if( !empty($fEspecialidadUri) ) {
    		$arrData['message'] = 'La URI ingresada ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$allInputs['titulo_seo'] = $this->input->post('titulo_seo');
   		$allInputs['meta_content_seo'] = $this->input->post('meta_content_seo');
   		$allInputs['descripcion_html'] = $this->input->post('descripcion_html');
   		
   		$allInputs['visible'] = $this->input->post('visible');
   		$allInputs['visible_home'] = $this->input->post('visible_home');
   		$allInputs['reserva_cita'] = $this->input->post('reserva_cita');
   		$allInputs['icono_home'] = 'default_proceso.png';
    	$allInputs['image_banner'] = 'default_proceso.png';
    	$this->db->trans_start();
    	if( !empty($_FILES['icono_home_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['icono_home_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_icono.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/especialidad/iconos-home','icono_home_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['icono_home'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['image_banner_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['image_banner_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_imagen.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/especialidad','image_banner_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['image_banner'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_especialidad->m_registrar($allInputs)) { 
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

    	$allInputs['idespecialidad'] = $this->input->post('idespecialidad');
    	$allInputs['nombre'] = $this->input->post('nombre');
    	$allInputs['uri'] = $this->input->post('uri');
    	$fEspecialidad = $this->model_especialidad->m_validar_especialidad($allInputs['nombre'],$allInputs['idespecialidad']);
    	if( !empty($fEspecialidad) ) {
    		$arrData['message'] = 'La especialidad ingresada ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$fEspecialidadUri = $this->model_especialidad->m_validar_especialidad_uri($allInputs['uri'],$allInputs['idespecialidad']);
    	if( !empty($fEspecialidadUri) ) {
    		$arrData['message'] = 'La URI ingresada ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$allInputs['titulo_seo'] = $this->input->post('titulo_seo');
   		$allInputs['meta_content_seo'] = $this->input->post('meta_content_seo');
   		$allInputs['descripcion_html'] = $this->input->post('descripcion_html');
   		$allInputs['visible'] = $this->input->post('visible');
   		$allInputs['visible_home'] = $this->input->post('visible_home');
   		$allInputs['reserva_cita'] = $this->input->post('reserva_cita');
    	$this->db->trans_start();
    	if( !empty($_FILES['icono_home_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['icono_home_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_icono.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/especialidad/iconos-home','icono_home_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['icono_home'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['image_banner_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['image_banner_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_imagen.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/especialidad','image_banner_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['image_banner'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_especialidad->m_editar($allInputs)) { 
			$arrData['message'] = 'Se editaron los datos correctamente';
			$arrData['flag'] = 1;
		}
		$this->db->trans_complete();
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function anular()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'No se pudo anular los datos';
    	$arrData['flag'] = 0;
		if( $this->model_especialidad->m_anular($allInputs) ){ 
			$arrData['message'] = 'Se anularon los datos correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function agregarEspecialidadMedico()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al agregar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	
		// VALIDACIONES
    	if( empty($allInputs['idmedico']) ){ 
    		$arrData['message'] = 'No registró todos los campos obligatorios.';
    		$arrData['flag'] = 0;
    		$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
		    return;
    	}
    	if( empty($allInputs['especialidad']['id']) ){ 
    		$arrData['message'] = 'No registró todos los campos obligatorios.';
    		$arrData['flag'] = 0;
    		$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
		    return;
    	}

    	$this->db->trans_start();
		if($this->model_especialidad->m_agregar_esp_medico($allInputs)) { // registro de horario 
			$arrData['message'] = 'Se agregaron los datos correctamente';
			$arrData['flag'] = 1;
		}
		$this->db->trans_complete();
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function editarEspecialidadMedico()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$arrData['message'] = 'Error al editar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// VALIDACIONES
		if($this->model_especialidad->m_editar_esp_medico($allInputs)){
			$arrData['message'] = 'Se editaron los datos correctamente';
			$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

	public function anularEspecialidadMedico()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		$arrData['message'] = 'No se pudo anular los datos';
    	$arrData['flag'] = 0;
		if( $this->model_especialidad->m_anular_esp_medico($allInputs) ){ 
			$arrData['message'] = 'Se anularon los datos correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}