<?php
class Model_horario extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_horario($paramPaginate,$paramDatos){
		$this->db->select('ho.idhorario, ho.dia, ho.hora_inicio, ho.hora_fin');
		$this->db->from('horario ho');
		$this->db->join('medico me', 'ho.idmedico = me.idmedico');
		$this->db->where('ho.estado', 1);
		$this->db->where('me.estado', 1);
		$this->db->where('me.idmedico', $paramDatos['idmedico']);
		if( $paramPaginate['sortName'] ){
			$this->db->order_by($paramPaginate['sortName'], $paramPaginate['sort']);
		}
		if( $paramPaginate['firstRow'] || $paramPaginate['pageSize'] ){
			$this->db->limit($paramPaginate['pageSize'],$paramPaginate['firstRow'] );
		}
		return $this->db->get()->result_array();
	}

	public function m_count_horario($paramPaginate,$paramDatos){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('horario ho');
		$this->db->join('medico me', 'ho.idmedico = me.idmedico');
		$this->db->where('ho.estado', 1);
		$this->db->where('me.estado', 1);
		$this->db->where('me.idmedico', $paramDatos['idmedico']);
		$fData = $this->db->get()->row_array();
		return $fData;
	}

	public function m_registrar($datos)
	{
		$data = array(
			'idmedico' => $datos['idmedico'],
			'dia' => $datos['dia'],
			'hora_inicio' => $datos['hora_inicio'],
			'hora_fin' => $datos['hora_fin']
		);
		return $this->db->insert('horario', $data);
	}

	public function m_editar($datos){ 
		$data = array( 
			'dia' => $datos['dia'],
			'hora_inicio' => $datos['hora_inicio'],
			'hora_fin' => $datos['hora_fin']
		);
		$this->db->where('idhorario',$datos['idhorario']);
		return $this->db->update('horario', $data);
	} 
	public function m_anular($datos)
	{
		$data = array( 
			'estado' => 0
		); 
		$this->db->where('idhorario',$datos['idhorario']);
		return $this->db->update('horario', $data);
	} 
}
?>
