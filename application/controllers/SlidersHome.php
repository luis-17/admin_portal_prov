<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SlidersHome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','imagen_helper','otros_helper','fechas_helper'));
		$this->load->model(array('model_sliders_home'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->sessionFactur = @$this->session->userdata('sess_fact_'.substr(base_url(),-20,7));
		date_default_timezone_set("America/Lima");
		//if(!@$this->user) redirect ('inicio/login');
		//$permisos = cargar_permisos_del_usuario($this->user->idusuario);
	}
	public function listar()
	{ 
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];
		$lista = $this->model_sliders_home->m_cargar_sliders_home($paramPaginate);
		$fCount = $this->model_sliders_home->m_count_sliders_home($paramPaginate);
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
					'idsliderhome' => $row['idsliderhome'],
					'lema' => $row['lema'],
					'lema_alt' => $row['lema_alt'],
					'link_button' => $row['link_button'],
					'text_button' => $row['text_button'],
					'image_background' => $row['image_background'],
					'image_lateral' => $row['image_lateral'],
					'visible' => (int)$row['visible'],
					'visible_obj' => array(
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
		$this->load->view('sliders-home/mant_slidersHome');
	}
	public function registrar()
	{
		$allInputs = array();
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

	    $allInputs['lema'] = $this->input->post('lema');
    	$allInputs['lema_alt'] = $this->input->post('lema_alt');
    	$allInputs['link_button'] = $this->input->post('link_button');
    	$allInputs['text_button'] = $this->input->post('text_button');
    	$allInputs['visible'] = $this->input->post('visible');
    	$allInputs['image_background'] = 'default_proceso.png';
    	$this->db->trans_start();
		if( !empty($_FILES['image_background_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['image_background_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = time().'_slide.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/slider','image_background_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['image_background'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		$allInputs['image_lateral'] = 'default_proceso.png';
		if( !empty($_FILES['image_lateral_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['image_lateral_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = time().'_slide.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/slider/lateral','image_lateral_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['image_lateral'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_sliders_home->m_registrar($allInputs)){
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
    	$allInputs['idsliderhome'] = $this->input->post('idsliderhome');
    	$allInputs['lema'] = $this->input->post('lema');
    	$allInputs['lema_alt'] = $this->input->post('lema_alt');
    	$allInputs['link_button'] = $this->input->post('link_button');
    	$allInputs['text_button'] = $this->input->post('text_button');
    	$allInputs['visible'] = $this->input->post('visible');

    	$this->db->trans_start();
		if( !empty($_FILES['image_background_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['image_background_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = time().'_slide.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/slider','image_background_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['image_background'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['image_lateral_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['image_lateral_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = time().'_slide.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/slider/lateral','image_lateral_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['image_lateral'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_sliders_home->m_editar($allInputs)){
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
		if( $this->model_sliders_home->m_eliminar($allInputs) ){ 
			$arrData['message'] = 'Se anuló el slide correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}