<?php
class Model_sliders_home extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_sliders_home($paramPaginate){ 
		$this->db->select("sh.idsliderhome, sh.lema, sh.lema_alt, sh.link_button, sh.text_button, sh.lema, 
			sh.image_background, sh.image_lateral, sh.visible");
		$this->db->from('slider_home sh');
		$this->db->where('estado', 1);
		if( isset($paramPaginate['search'] ) && $paramPaginate['search'] ){
			foreach ($paramPaginate['searchColumn'] as $key => $value) {
				if(! empty($value)){
					$this->db->like($key ,strtoupper_total($value) ,FALSE);
				}
			}
		}

		if( $paramPaginate['sortName'] ){
			$this->db->order_by($paramPaginate['sortName'], $paramPaginate['sort']);
		}
		if( $paramPaginate['firstRow'] || $paramPaginate['pageSize'] ){
			$this->db->limit($paramPaginate['pageSize'],$paramPaginate['firstRow'] );
		}
		return $this->db->get()->result_array();
	}

	public function m_count_sliders_home($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('slider_home sh');
		$this->db->where('estado', 1);
		if( isset($paramPaginate['search'] ) && $paramPaginate['search'] ){
			foreach ($paramPaginate['searchColumn'] as $key => $value) {
				if(! empty($value)){
					$this->db->like($key ,strtoupper_total($value) ,FALSE);
				}
			}
		}
		$fData = $this->db->get()->row_array();
		return $fData;
	}

	public function m_registrar($datos)
	{
		$data = array(
			'lema' => $datos['lema'],
			'lema_alt' => $datos['lema_alt'],	
			'link_button' => $datos['link_button'],	
			'text_button' => $datos['text_button'],	
			'image_background' => $datos['image_background'],
			'image_lateral' => $datos['image_lateral'],
			'visible' => $datos['visible']
		);
		return $this->db->insert('slider_home', $data); 
	}

	public function m_editar($datos){
		$data = array(
			'lema' => $datos['lema'],
			'lema_alt' => $datos['lema_alt'],	
			'link_button' => $datos['link_button'],	
			'text_button' => $datos['text_button'],	
			'visible' => $datos['visible'],
		);
		if( !empty($datos['image_background']) ){
			$data['image_background'] = $datos['image_background'];
		}
		if( !empty($datos['image_lateral']) ){
			$data['image_lateral'] = $datos['image_lateral'];
		}
		$this->db->where('idsliderhome',$datos['idsliderhome']);
		return $this->db->update('slider_home', $data);
	}

	public function m_eliminar($datos)
	{
		$data = array(
			'estado' => 0,
		);
		$this->db->where('idsliderhome',$datos['idsliderhome']);
		return $this->db->update('slider_home', $data);
	}
}
