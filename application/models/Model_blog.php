<?php
class Model_blog extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function m_cargar_blog($paramPaginate){ 
		$this->db->select("bl.idblog, bl.titulo, bl.contenido_html, bl.autor, bl.cargo_autor, bl.foto_autor, bl.titulo_seo, bl.meta_content_seo, 
			bl.imagen_preview, bl.imagen_portada, bl.uri, bl.fecha_publicacion, bl.embed_video, bl.visible, bl.estado");
		$this->db->from('blog bl');
		$this->db->where('bl.estado', 1);
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

	public function m_count_blog($paramPaginate){
		$this->db->select('COUNT(*) AS contador');
		$this->db->from('blog bl');
		$this->db->where('bl.estado', 1);
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
	public function m_validar_blog_uri($uri, $id = NULL)
	{
		$this->db->select("bl.idblog");
		$this->db->from('blog bl');
		$this->db->where('bl.uri', $uri);
		$this->db->where('bl.estado', 1);
		if($id){
			$this->db->where('bl.idblog <>', $id);
		}
		$this->db->limit(1);
		return $this->db->get()->row_array();
	}
	public function m_registrar($datos)
	{
		$data = array(
			'titulo'=> $datos['titulo'],
			'contenido_html'=> nl2br($datos['contenido_html']),
			'autor'=> $datos['autor'],
			'cargo_autor'=> $datos['cargo_autor'],
			'titulo_seo'=> $datos['titulo_seo'],
			'meta_content_seo'=> $datos['meta_content_seo'],
			'foto_autor'=> $datos['foto_autor'],
			'imagen_preview'=> $datos['imagen_preview'],
			'imagen_portada'=> $datos['imagen_portada'],
			'uri' => $datos['uri'],
			'fecha_publicacion'=> $datos['fecha_publicacion'],
			'fecha_creacion'=> date('Y-m-d H:i:s'),
			'visible'=> $datos['visible'],
			'embed_video'=> empty($datos['embed_video']) ? NULL : $datos['embed_video']
		);
		return $this->db->insert('blog', $data);
	}

	public function m_editar($datos)
	{
		$data = array(
			'titulo'=> $datos['titulo'],
			'contenido_html'=> nl2br($datos['contenido_html']),
			'autor'=> $datos['autor'],
			'cargo_autor'=> $datos['cargo_autor'],
			'titulo_seo'=> $datos['titulo_seo'],
			'meta_content_seo'=> $datos['meta_content_seo'],
			// 'foto_autor'=> $datos['foto_autor'],
			// 'imagen_preview'=> $datos['imagen_preview'],
			// 'imagen_portada'=> $datos['imagen_portada'],
			'uri' => $datos['uri'],
			'fecha_publicacion'=> $datos['fecha_publicacion'],
			'fecha_creacion'=> date('Y-m-d H:i:s'),
			'visible'=> $datos['visible'],
			'embed_video'=> empty($datos['embed_video']) ? NULL : $datos['embed_video']
		);
		// var_dump($data, 'daaatatataat'); exit();
		if( !empty($datos['foto_autor']) ){
			$data['foto_autor'] = $datos['foto_autor'];
		}
		if( !empty($datos['imagen_preview']) ){
			$data['imagen_preview'] = $datos['imagen_preview'];
		}
		if( !empty($datos['imagen_portada']) ){
			$data['imagen_portada'] = $datos['imagen_portada'];
		}
		$this->db->where('idblog',$datos['idblog']);
		return $this->db->update('blog', $data);
	}

	public function m_anular($datos)
	{
		$data = array( 
			'estado' => 0
		);
		$this->db->where('idblog',$datos['idblog']); 
		return $this->db->update('blog', $data); 
	}
}
?>