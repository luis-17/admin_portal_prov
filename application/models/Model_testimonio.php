<?php
class Model_testimonio extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_testimonio($paramPaginate){ 
		$this->db->select("te.idtestimonio, te.paciente, te.foto, te.testimonio_html, te.visible");
		$this->db->from('testimonio te');
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

	public function m_count_testimonio($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('testimonio te');
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
			'paciente' => $datos['paciente'],	
			'foto' => $datos['foto'],
			'testimonio_html' => $datos['testimonio_html'],
			'visible' => $datos['visible']
		);
		return $this->db->insert('testimonio', $data); 
	}

	public function m_editar($datos){
		$data = array(
			'paciente' => $datos['paciente'],
			'testimonio_html' => $datos['testimonio_html'],
			'visible' => $datos['visible'],
		);
		if( !empty($datos['foto']) ){
			$data['foto'] = $datos['foto'];
		}
		$this->db->where('idtestimonio',$datos['idtestimonio']);
		return $this->db->update('testimonio', $data);
	}

	public function m_eliminar($datos)
	{
		$data = array(
			'estado' => 0,
		);
		$this->db->where('idtestimonio',$datos['idtestimonio']);
		return $this->db->update('testimonio', $data);
	}
}
