<?php
class Model_servicio extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_servicio($paramPaginate){ 
		$this->db->select("se.idservicio, se.nombre, se.descripcion_html, se.como_acceder, se.alias, se.imagen_portada, 
			se.icono_servicio, se.icono_servicio_lg, se.visible, se.visible_esp, se.visible_menu, se.embed_video, se.titulo_seo, se.meta_content_seo");
		$this->db->from('servicio se');
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

	public function m_count_servicio($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('servicio se');
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

	// public function m_cargar_cbo()
	// {
	// 	$this->db->select("se.idservicio, se.nombre");
	// 	$this->db->from('servicio se');
	// 	$this->db->where('estado', 1);
	// 	return $this->db->get()->result_array();
	// }

	public function m_validar_servicio($nombre, $id = NULL)
	{
		$this->db->select("se.idservicio");
		$this->db->from('servicio se');
		$this->db->where('se.nombre', $nombre);
		if($id){
			$this->db->where('se.idservicio <>', $id);
		}
		$this->db->where('estado', 1); //activo
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	public function m_validar_servicio_uri($uri, $id = NULL)
	{
		$this->db->select("se.idservicio");
		$this->db->from('servicio se');
		$this->db->where('se.alias', $uri);
		$this->db->where('estado', 1); //activo
		if($id){
			$this->db->where('se.idservicio <>', $id);
		}
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	public function m_registrar($datos)
	{
		$data = array(
			'nombre'=> $datos['nombre'],
			'descripcion_html'=> nl2br($datos['descripcion_html']),
			'como_acceder'=> empty($datos['como_acceder']) ? NULL : nl2br($datos['como_acceder']),
			'imagen_portada'=> $datos['imagen_portada'],
			'icono_servicio'=> $datos['icono_servicio'],
			'icono_servicio_lg'=> $datos['icono_servicio_lg'],
			'titulo_seo' => $datos['titulo_seo'],
			'meta_content_seo' => $datos['meta_content_seo'],
			'alias' => $datos['alias'],
			'visible'=> $datos['visible'],
			'visible_menu'=> $datos['visible_menu'],
			'visible_esp'=> $datos['visible_esp'],
			'embed_video'=> empty($datos['embed_video']) ? NULL : $datos['embed_video']
			// 'tiene_landing'=> $datos['tiene_landing']
		);
		return $this->db->insert('servicio', $data);
	}

	public function m_editar($datos)
	{
		$data = array(
			'nombre'=> $datos['nombre'],
			'descripcion_html'=> nl2br($datos['descripcion_html']),
			'como_acceder'=> empty($datos['como_acceder']) ? NULL : nl2br($datos['como_acceder']),
			'titulo_seo' => $datos['titulo_seo'],
			'meta_content_seo' => $datos['meta_content_seo'],
			'alias' => $datos['alias'],
			'visible'=> $datos['visible'],
			'visible_menu'=> $datos['visible_menu'],
			'visible_esp'=> $datos['visible_esp'],
			'embed_video'=> empty($datos['embed_video']) ? NULL : $datos['embed_video']
		);
		// var_dump($data, 'daaatatataat'); exit();
		if( !empty($datos['icono_servicio']) ){
			$data['icono_servicio'] = $datos['icono_servicio'];
		}
		if( !empty($datos['icono_servicio_lg']) ){
			$data['icono_servicio_lg'] = $datos['icono_servicio_lg'];
		}
		if( !empty($datos['imagen_portada']) ){
			$data['imagen_portada'] = $datos['imagen_portada'];
		}
		$this->db->where('idservicio',$datos['idservicio']);
		return $this->db->update('servicio', $data);
	}

	public function m_anular($datos)
	{
		$data = array( 
			'estado' => 0
		);
		$this->db->where('idservicio',$datos['idservicio']); 
		return $this->db->update('servicio', $data); 
	}
}
?>