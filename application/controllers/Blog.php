<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        $this->sessionFactur = @$this->session->userdata('sess_fact_'.substr(base_url(),-20,7));
        // $this->load->helper(array('fechas','otros')); 
        $this->load->helper(array('imagen_helper','otros_helper','fechas_helper'));
        $this->load->model(array('model_blog')); 
    }

	public function listar(){ 
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];
		$lista = $this->model_blog->m_cargar_blog($paramPaginate);
		$fCount = $this->model_blog->m_count_blog($paramPaginate);
		$arrListado = array();
		foreach ($lista as $row) { 
			$strDescripcion = 'OCULTO';
			$strClaseLabel = ' label-default';
			if($row['visible'] === '1'){
				$strDescripcion = 'VISIBLE';
				$strClaseLabel = ' label-success';
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
					'idblog' => $row['idblog'],
					'titulo' => $row['titulo'],
					'contenido_html' => $row['contenido_html'],
					'autor' => $row['autor'],
					'cargo_autor' => $row['cargo_autor'],
					'embed_video' => $row['embed_video'],
					'foto_autor' => $row['foto_autor'],
					'imagen_preview' => $row['imagen_preview'],
					'imagen_portada' => $row['imagen_portada'],
					'uri' => $row['uri'],
					'fecha_publicacion'=> $row['fecha_publicacion'],
					'visible' => (int)$row['visible'],
					'visible_obj' => array(
						'claseLabel' => $strClaseLabel,
						'visible' => $row['visible'],
						'labelText'=> $strDescripcion
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
		$this->load->view('blog/mant_blog');
	}
	public function registrar()
	{
		// $allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$allInputs = array();
		$arrData['message'] = 'Error al registrar los datos, inténtelo nuevamente';
    	$arrData['flag'] = 0;
    	// VALIDACIONES
    	$allInputs['uri'] = $this->input->post('uri');
    	$fBlogURI = $this->model_blog->m_validar_blog_uri($allInputs['uri']);
    	if( !empty($fBlogURI) ) {
    		$arrData['message'] = 'El URI ingresado ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$allInputs['titulo'] = $this->input->post('titulo');
   		$allInputs['contenido_html'] = $this->input->post('contenido_html');
   		$allInputs['visible'] = $this->input->post('visible');
   		$allInputs['autor'] = $this->input->post('autor');
   		$allInputs['cargo_autor'] = $this->input->post('cargo_autor');
   		$allInputs['fecha_publicacion'] = $this->input->post('fecha_publicacion');
   		$allInputs['embed_video'] = $this->input->post('embed_video');
   		if($allInputs['embed_video'] === 'null'){
   			$allInputs['embed_video'] = NULL;
   		}
   		$allInputs['foto_autor'] = 'default_proceso_100.png';
   		$allInputs['imagen_preview'] = 'default_proceso.png';
    	$allInputs['imagen_portada'] = 'default_proceso.png';
    	$this->db->trans_start();
    	if( !empty($_FILES['foto_autor_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['foto_autor_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_fotoautor.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/blog/foto-autor','foto_autor_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['foto_autor'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['imagen_preview_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['imagen_preview_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_preview.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/blog','imagen_preview_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['imagen_preview'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['imagen_portada_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['imagen_portada_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_portada.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/blog/portadas','imagen_portada_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['imagen_portada'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_blog->m_registrar($allInputs)) { 
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

    	$allInputs['idblog'] = $this->input->post('idblog');
    	$allInputs['uri'] = $this->input->post('uri');
   		$fBlogUri = $this->model_blog->m_validar_blog_uri($allInputs['uri'],$allInputs['idblog']);
    	if( !empty($fBlogUri) ) {
    		$arrData['message'] = 'La URI ingresada ya existe.';
			$arrData['flag'] = 0;
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($arrData));
			return;
   		}
   		$allInputs['titulo'] = $this->input->post('titulo');
   		$allInputs['contenido_html'] = $this->input->post('contenido_html');
   		$allInputs['visible'] = $this->input->post('visible');
   		$allInputs['autor'] = $this->input->post('autor');
   		$allInputs['cargo_autor'] = $this->input->post('cargo_autor');
   		$allInputs['fecha_publicacion'] = $this->input->post('fecha_publicacion');
   		$allInputs['embed_video'] = $this->input->post('embed_video');
   		if($allInputs['embed_video'] === 'null'){
   			$allInputs['embed_video'] = NULL;
   		}
    	$this->db->trans_start();
    	if( !empty($_FILES['foto_autor_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['foto_autor_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_fotoautor.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/blog/foto-autor','foto_autor_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['foto_autor'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['imagen_preview_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['imagen_preview_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_preview.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/blog','imagen_preview_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['imagen_preview'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if( !empty($_FILES['imagen_portada_blob']) ){
			$allInputs['extension'] = pathinfo($_FILES['imagen_portada_blob']['name'], PATHINFO_EXTENSION);
    		$allInputs['nuevoNombreArchivo'] = $allInputs['uri'].'_portada.'.$allInputs['extension'];
    		if( subir_fichero('assets/dinamic/blog/portadas','imagen_portada_blob',$allInputs['nuevoNombreArchivo']) ){
				$allInputs['imagen_portada'] = $allInputs['nuevoNombreArchivo'];
			}
		}
		if($this->model_blog->m_editar($allInputs)) { 
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
		if( $this->model_blog->m_anular($allInputs) ){ 
			$arrData['message'] = 'Se anularon los datos correctamente';
    		$arrData['flag'] = 1;
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
}