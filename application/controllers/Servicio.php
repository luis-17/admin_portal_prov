<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        $this->sessionFactur = @$this->session->userdata('sess_fact_'.substr(base_url(),-20,7));
        // $this->load->helper(array('fechas','otros')); 
        $this->load->helper(array('imagen_helper','otros_helper','fechas_helper'));
        $this->load->model(array('model_servicio')); 
    }

	public function listar(){ 
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];
		$lista = $this->model_servicio->m_cargar_servicio($paramPaginate);
		$fCount = $this->model_servicio->m_count_servicio($paramPaginate);
		$arrListado = array();
		foreach ($lista as $row) { 
			$strDescripcion = 'OCULTO';
			$strClaseLabel = ' label-default';
			if($row['visible'] === '1'){
				$strDescripcion = 'VISIBLE';
				$strClaseLabel = ' label-success';
			}
			$strDescripcionMenu = 'OCULTO';
			$strClaseLabelMenu = ' label-default';
			if($row['visible_menu'] === '1'){
				$strDescripcionMenu = 'VISIBLE';
				$strClaseLabelMenu = ' label-success';
			}
			$strDescripcionEsp = 'OCULTO';
			$strClaseLabelEsp = ' label-default';
			if($row['visible_esp'] === '1'){
				$strDescripcionEsp = 'VISIBLE';
				$strClaseLabelEsp = ' label-success';
			}
			$strDescripcionVideo = null;
			$strClaseLabelVideo = null;
			$strVideo = null;
			if( !empty($row['embed_video']) ){
				$strDescripcionVideo = 'VIDEO!';
				$strClaseLabelVideo = ' label-success';
				$strVideo = 'si';
			}
			array_push($arrListado,
				array(
					'idservicio' => $row['idservicio'],
					'nombre' => $row['nombre'],
					'descripcion_html' => $row['descripcion_html'],
					'como_acceder' => $row['como_acceder'],
					'alias' => $row['alias'],
					'embed_video' => $row['embed_video'],
					'imagen_servicio' => $row['imagen_servicio'],
					'icono_servicio' => $row['icono_servicio'],
					'icono_servicio_lg' => $row['icono_servicio_lg'],
					'visible' => (int)$row['visible'],
					'visible_menu' => (int)$row['visible_menu'],
					'visible_esp' => (int)$row['visible_esp'],
					'visible_obj' => array(
						'claseLabel' => $strClaseLabel,
						'visible' => $row['visible'],
						'labelText'=> $strDescripcion
					),
					'visible_menu_obj' => array(
						'claseLabel' => $strClaseLabelMenu,
						'visible' => $row['visible_menu'],
						'labelText'=> $strDescripcionMenu
					),
					'visible_esp_obj' => array(
						'claseLabel' => $strClaseLabelEsp,
						'visible' => $row['visible_esp'],
						'labelText'=> $strDescripcionEsp
					),
					'embed_obj' => array(
						'claseLabel' => $strClaseLabelVideo,
						'labelText'=> $strDescripcionVideo,
						'video'=> $strVideo
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
		$this->load->view('servicio/mant_servicio');
	}
	public function registrar()
	{
		// $allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$allInputs = array();
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// VALIDACIONES
    	$allInputs['nombre'] = $this->input->post('nombre');
    	$allInputs['alias'] = $this->input->post('alias');
    	$fServicio = $this->model_servicio->m_validar_servicio($allInputs['nombre']);
    	if( !empty($fServicio) ) {
    		$arrData['message'] = 'El servicio ingresado ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$fServicioUri = $this->model_servicio->m_validar_servicio_uri($allInputs['alias']);
    	if( !empty($fServicioUri) ) {
    		$arrData['message'] = 'La URI ingresada ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$allInputs['descripcion_html'] = $this->input->post('descripcion_html');
   		$allInputs['como_acceder'] = $this->input->post('como_acceder');
   		
   		$allInputs['visible'] = $this->input->post('visible');
   		$allInputs['visible_menu'] = $this->input->post('visible_menu');
   		$allInputs['visible_esp'] = $this->input->post('visible_esp');
   		$allInputs['embed_video'] = $this->input->post('embed_video');
   		if($allInputs['embed_video'] === 'null'){
   			$allInputs['embed_video'] = NULL;
   		}
   		$allInputs['icono_servicio'] = 'default_proceso_100.png';
   		$allInputs['icono_servicio_lg'] = 'default_proceso.png';
    	$allInputs['imagen_servicio'] = 'default_proceso.png';
    	$this->db->trans_start();
    	if( !empty($_FILES['icono_servicio_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['icono_servicio_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['alias'].'_icono.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/servicio','icono_servicio_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['icono_servicio'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['icono_servicio_lg_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['icono_servicio_lg_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['alias'].'_icono.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/servicio/iconos-lg','icono_servicio_lg_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['icono_servicio_lg'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['imagen_servicio_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['imagen_servicio_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['alias'].'_imagen.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/servicio/imagenes','imagen_servicio_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['imagen_servicio'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_servicio->m_registrar($allInputs)) { 
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

    	$allInputs['idservicio'] = $this->input->post('idservicio');
    	$allInputs['nombre'] = $this->input->post('nombre');
    	$allInputs['alias'] = $this->input->post('alias');
    	$fServicio = $this->model_servicio->m_validar_servicio($allInputs['nombre'],$allInputs['idservicio']);
    	if( !empty($fServicio) ) {
    		$arrData['message'] = 'El servicio ingresado ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$fEspecialidadUri = $this->model_servicio->m_validar_servicio_uri($allInputs['alias'],$allInputs['idservicio']);
    	if( !empty($fEspecialidadUri) ) {
    		$arrData['message'] = 'La URI ingresada ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$allInputs['descripcion_html'] = $this->input->post('descripcion_html');
   		$allInputs['como_acceder'] = $this->input->post('como_acceder');
   		$allInputs['visible'] = $this->input->post('visible');
   		$allInputs['visible_menu'] = $this->input->post('visible_menu');
   		$allInputs['visible_esp'] = $this->input->post('visible_esp');
   		$allInputs['embed_video'] = $this->input->post('embed_video');
   		// var_dump($allInputs['embed_video']); 
   		if($allInputs['embed_video'] === 'null'){
   			// var_dump('entrasteee'); exit();
   			$allInputs['embed_video'] = NULL;
   		}
    	$this->db->trans_start();
    	if( !empty($_FILES['icono_servicio_lg_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['icono_servicio_lg_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['alias'].'_icono.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/servicio/iconos-lg','icono_servicio_lg_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['icono_servicio_lg'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['icono_servicio_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['icono_servicio_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['alias'].'_icono.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/servicio','icono_servicio_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['icono_servicio'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['imagen_servicio_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['imagen_servicio_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['alias'].'_imagen.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/servicio/imagenes','imagen_servicio_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['imagen_servicio'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_servicio->m_editar($allInputs)) { 
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
		if( $this->model_servicio->m_anular($allInputs) ){ 
			$arrData['message'] = 'Se anularon los datos correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}