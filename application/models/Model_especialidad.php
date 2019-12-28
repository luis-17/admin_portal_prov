<?php
class Model_especialidad extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_especialidad($paramPaginate){ 
		$this->db->select("es.idespecialidad, es.nombre, es.descripcion_html, es.uri, es.image_banner, es.titulo_seo, es.meta_content_seo, 
			es.icono_home, es.visible, es.visible_home, es.reserva_cita, es.tiene_landing");
		$this->db->from('especialidad es');
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

	public function m_count_especialidad($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('especialidad es');
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

	public function m_cargar_especialidades_medico($paramPaginate, $paramDatos)
	{
		$this->db->select("es.idespecialidad, es.nombre, es.uri, em.idespecialidadmedico, me.idmedico");
		$this->db->from('especialidad es');
		$this->db->join('especialidad_medico em', 'es.idespecialidad = em.idespecialidad');
		$this->db->join('medico me', 'em.idmedico = me.idmedico');
		$this->db->where('estado_em', 1);
		$this->db->where('es.estado', 1);
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

	public function m_count_especialidades_medico($paramPaginate, $paramDatos)
	{
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('especialidad es');
		$this->db->join('especialidad_medico em', 'es.idespecialidad = em.idespecialidad');
		$this->db->join('medico me', 'em.idmedico = me.idmedico');
		$this->db->where('em.estado_em', 1);
		$this->db->where('es.estado', 1);
		$this->db->where('me.estado', 1);
		$this->db->where('me.idmedico', $paramDatos['idmedico']);
		$fData = $this->db->get()->row_array();
		return $fData;
	}

	public function m_cargar_cbo()
	{
		$this->db->select("es.idespecialidad, es.nombre");
		$this->db->from('especialidad es');
		$this->db->where('estado', 1);
		return $this->db->get()->result_array();
	}

	public function m_validar_especialidad($nombre, $id = NULL)
	{
		$this->db->select("es.idespecialidad");
		$this->db->from('especialidad es');
		$this->db->where('es.nombre', $nombre);
		if($id){
			$this->db->where('es.idespecialidad <>', $id);
		}
		$this->db->where('estado', 1); //activo
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	public function m_validar_especialidad_uri($uri, $id = NULL)
	{
		$this->db->select("es.idespecialidad");
		$this->db->from('especialidad es');
		$this->db->where('es.uri', $uri);
		$this->db->where('estado', 1); //activo
		if($id){
			$this->db->where('es.idespecialidad <>', $id);
		}
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	public function m_registrar($datos)
	{
		$data = array(
			'nombre' => strtoupper($datos['nombre']),
			'descripcion_html' => nl2br($datos['descripcion_html']),
			'titulo_seo' => $datos['titulo_seo'],
			'meta_content_seo' => $datos['meta_content_seo'],
			'uri' => $datos['uri'],
			'visible'=> $datos['visible'],
			'visible_home'=> $datos['visible_home'],
			'reserva_cita'=> $datos['reserva_cita']
			// 'tiene_landing'=> $datos['tiene_landing']
		);
		return $this->db->insert('especialidad', $data); 
	}

	public function m_editar($datos)
	{
		$data = array(
			'nombre' => strtoupper($datos['nombre']),
			'descripcion_html' => nl2br($datos['descripcion_html']),
			'titulo_seo' => $datos['titulo_seo'],
			'meta_content_seo' => $datos['meta_content_seo'],
			'uri' => $datos['uri'],
			'visible'=> $datos['visible'],
			'visible_home'=> $datos['visible_home'],
			'reserva_cita'=> $datos['reserva_cita']
			// 'tiene_landing'=> $datos['tiene_landing']
		);
		if( !empty($datos['icono_home']) ){
			$data['icono_home'] = $datos['icono_home'];
		}
		if( !empty($datos['image_banner']) ){
			$data['image_banner'] = $datos['image_banner'];
		}
		$this->db->where('idespecialidad',$datos['idespecialidad']);
		return $this->db->update('especialidad', $data); 
	}

	public function m_anular($datos)
	{
		$data = array( 
			'estado' => 2
		);
		$this->db->where('idespecialidad',$datos['idespecialidad']); 
		return $this->db->update('especialidad', $data); 
	}

	public function m_agregar_esp_medico($datos)
	{
		$data = array(
			'idespecialidad' => $datos['especialidad']['id'],
			'idmedico' => $datos['idmedico']
		);
		return $this->db->insert('especialidad_medico', $data); 
	}
	public function m_editar_esp_medico($datos)
	{
		$data = array(
			'idespecialidad' => $datos['especialidad']['id']
		);
		$this->db->where('idespecialidadmedico',$datos['idespecialidadmedico']);
		return $this->db->update('especialidad_medico', $data); 
	}
	public function m_anular_esp_medico($datos)
	{
		$data = array( 
			'estado_em' => 2
		);
		$this->db->where('idespecialidadmedico',$datos['idespecialidadmedico']); 
		return $this->db->update('especialidad_medico', $data); 
	}
}
?>