<?php
class Model_promocion extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_promocion($paramPaginate){ 
		$this->db->select("pr.idpromocion, pr.titulo, pr.foto, pr.visible");
		$this->db->from('promocion pr');
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

	public function m_count_promocion($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('promocion te');
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
			'titulo' => $datos['titulo'],	
			'foto' => $datos['foto'],
			'visible' => $datos['visible']
		);
		return $this->db->insert('promocion', $data); 
	}

	public function m_editar($datos){
		$data = array(
			'titulo' => $datos['titulo'],
			'visible' => $datos['visible'],
		);
		if( !empty($datos['foto']) ){
			$data['foto'] = $datos['foto'];
		}
		$this->db->where('idpromocion',$datos['idpromocion']);
		return $this->db->update('promocion', $data);
	}

	public function m_eliminar($datos)
	{
		$data = array(
			'estado' => 0,
		);
		$this->db->where('idpromocion',$datos['idpromocion']);
		return $this->db->update('promocion', $data);
	}
}
