<?php
class Model_pagina extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_pagina($paramPaginate){ 
		$this->db->select("pag.idpagina, pag.nombre, pag.titulo_seo, pag.meta_content_seo");
		$this->db->from('pagina pag');
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

	public function m_count_pagina($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('pagina pag');
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

	public function m_editar($datos){
		$data = array(
			'titulo_seo' => $datos['titulo_seo'],
			'meta_content_seo' => $datos['meta_content_seo']
		);
		$this->db->where('idpagina',$datos['idpagina']);
		return $this->db->update('pagina', $data);
	}
}
