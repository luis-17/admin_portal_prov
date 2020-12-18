<?php
class Model_seguro extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_seguro($paramPaginate){ 
		$this->db->select("se.idseguro, se.nombre, se.logo, se.visible");
		$this->db->from('seguro se');
		$this->db->where('se.estado_seg', 1);
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

	public function m_count_seguro($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('seguro se');
		$this->db->where('se.estado_seg', 1);
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
			'nombre' => $datos['nombre'],	
			'logo' => $datos['logo'],
			'visible' => $datos['visible']
		);
		return $this->db->insert('seguro', $data); 
	}

	public function m_editar($datos){
		$data = array(
			'nombre' => $datos['nombre'],
			'visible' => $datos['visible'],
		);
		if( !empty($datos['logo']) ){
			$data['logo'] = $datos['logo'];
		}
		$this->db->where('idseguro',$datos['idseguro']);
		return $this->db->update('seguro', $data);
	}

	public function m_eliminar($datos)
	{
		$data = array(
			'estado_seg' => 0,
		);
		$this->db->where('idseguro',$datos['idseguro']);
		return $this->db->update('seguro', $data);
	}
}
