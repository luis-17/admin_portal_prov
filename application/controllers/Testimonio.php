<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','imagen_helper','otros_helper','fechas_helper'));
		$this->load->model(array('model_testimonio'));
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
		$lista = $this->model_testimonio->m_cargar_testimonio($paramPaginate);
		$fCount = $this->model_testimonio->m_count_testimonio($paramPaginate);
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
					'idtestimonio' => $row['idtestimonio'],
					'paciente' => $row['paciente'],
					'testimonio_html' => $row['testimonio_html'],
					'foto' => $row['foto'],
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
		$this->load->view('testimonio/mant_testimonio');
	}
	public function registrar()
	{
		$allInputs = array();
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;

	    $allInputs['paciente'] = $this->input->post('paciente');
    	$allInputs['testimonio_html'] = $this->input->post('testimonio_html');
    	$allInputs['visible'] = $this->input->post('visible');
    	$allInputs['foto'] = 'default_proceso.png';
    	$this->db->trans_start();
		if( !empty($_FILES['foto_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['foto_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['paciente'].'_tes.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/testimonio','foto_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['foto'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_testimonio->m_registrar($allInputs)){
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
    	$allInputs['idtestimonio'] = $this->input->post('idtestimonio');
    	$allInputs['paciente'] = $this->input->post('paciente');
    	$allInputs['testimonio_html'] = $this->input->post('testimonio_html');
    	$allInputs['visible'] = $this->input->post('visible');

    	$this->db->trans_start();
		if( !empty($_FILES['foto_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['foto_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['paciente'].'_tes.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/testimonio','foto_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['foto'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_testimonio->m_editar($allInputs)){
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
		if( $this->model_testimonio->m_eliminar($allInputs) ){ 
			$arrData['message'] = 'Se anuló el testimonio correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}