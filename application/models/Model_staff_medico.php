<?php
class Model_staff_medico extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	public function m_cargar_staff($paramPaginate=FALSE){
		$this->db->select('me.idmedico, me.nombres, me.ap_paterno, me.ap_materno, me.cmp, me.rne, me.lema, 
			me.estudios_html, me.foto, me.foto_perfil, me.visible, me.sexo');
		$this->db->from('medico me');			
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
		$this->db->where('estado', 1);
		return $this->db->get()->result_array();
	}
	public function m_count_staff($paramPaginate=FALSE){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('medico me');
		if( isset($paramPaginate['search'] ) && $paramPaginate['search'] ){
			foreach ($paramPaginate['searchColumn'] as $key => $value) {
				if(! empty($value)){
					$this->db->like($key ,strtoupper_total($value) ,FALSE);
				}
			}
		}
		$this->db->where('estado', 1);
		$fData = $this->db->get()->row_array();
		return $fData;
	}
	public function m_validar_cmp($cmp)
	{
		$this->db->select("me.idmedico");
		$this->db->from('medico me');
		$this->db->where('me.cmp', $cmp);
		$this->db->where('estado', 1); //activo
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	public function m_validar_cmp_edit($cmp,$idmedico)
	{
		$this->db->select("me.idmedico");
		$this->db->from('medico me');
		$this->db->where('me.cmp', $cmp); 
		$this->db->where('me.idmedico <>', $idmedico);
		$this->db->where('estado', 1); //activo
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	public function m_registrar($datos)
	{
		$data = array(
			'nombres' => strtoupper($datos['nombres']), 
			'ap_paterno' => strtoupper($datos['ap_paterno']),
			'ap_materno' => strtoupper($datos['ap_materno']),	
			'cmp' => $datos['cmp'],
			'rne' => $datos['rne'],
			'sexo' => $datos['sexo'],
			'lema' => empty($datos['lema']) ? NULL : $datos['lema'],
			'estudios_html' => empty($datos['estudios_html']) ? NULL : $datos['estudios_html'],	
			'foto' => $datos['foto'],
			'foto_perfil' => $datos['foto_perfil'],
			'visible' => 1,
		);
		return $this->db->insert('medico', $data);
	}	
	public function m_editar($datos){
		$data = array(
			'nombres' => strtoupper($datos['nombres']), 
			'ap_paterno' => strtoupper($datos['ap_paterno']),
			'ap_materno' => strtoupper($datos['ap_materno']),	
			'cmp' => $datos['cmp'],
			'rne' => $datos['rne'],
			'sexo' => $datos['sexo'],
			'lema' => empty($datos['lema']) ? NULL : $datos['lema'],
			'estudios_html' => empty($datos['estudios_html']) ? NULL : $datos['estudios_html']
			// 'foto' => $datos['foto'],
			// 'foto_perfil' => $datos['foto_perfil'],
			// 'visible' => $datos['visible'],
		);
		if( !empty($datos['foto']) ){
			$data['foto'] = $datos['foto'];
		}
		if( !empty($datos['foto_perfil']) ){
			$data['foto_perfil'] = $datos['foto_perfil'];
		}
		$this->db->where('idmedico',$datos['idmedico']);
		return $this->db->update('medico', $data);
	}

	public function m_ocultar($datos)
	{
		$data = array(
			'visible' => 0,
		);
		$this->db->where('idmedico',$datos['idmedico']);
		return $this->db->update('medico', $data);
	}
	public function m_mostrar($datos)
	{
		$data = array(
			'visible' => 1,
		);
		$this->db->where('idmedico',$datos['idmedico']);
		return $this->db->update('medico', $data);
	}
	public function m_eliminar($datos)
	{
		$data = array(
			'estado' => 2,
		);
		$this->db->where('idmedico',$datos['idmedico']);
		return $this->db->update('medico', $data);
	}
}
?>