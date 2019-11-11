<?php
class Model_convenio extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_convenio($paramPaginate){ 
		$this->db->select("cv.idconvenio, cv.descripcion, cv.visible");
		$this->db->from('convenio cv');
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

	public function m_count_convenio($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('convenio te');
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
			'descripcion' => $datos['descripcion'],	
			'visible' => $datos['visible']
		);
		return $this->db->insert('convenio', $data); 
	}

	public function m_editar($datos){
		$data = array(
			'descripcion' => $datos['descripcion'],
			'visible' => $datos['visible'],
		);
		$this->db->where('idconvenio',$datos['idconvenio']);
		return $this->db->update('convenio', $data);
	}

	public function m_eliminar($datos)
	{
		$data = array(
			'estado' => 0,
		);
		$this->db->where('idconvenio',$datos['idconvenio']);
		return $this->db->update('convenio', $data);
	}
}
