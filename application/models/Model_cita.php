<?php
class Model_cita extends CI_Model {
	public function __construct()
	{
		parent::__construct();
		
	}

	public function m_cargar_citas($paramPaginate, $paramDatos){
		$dbCitas = $this->load->database('citas', TRUE);
    $dbCitas->select("ci.idcita, ci.fecha_registro, ci.fecha_cita, ci.hora_inicio, ci.hora_fin, ci.medico, ci.especialidad, ci.estado_cita, 
			ci.idcitaspring, cl.idcliente, cl.nombres, cl.apellido_paterno, cl.apellido_materno, cl.tipo_documento, cl.numero_documento, 
			cl.correo, cl.telefono, ga.idgarante, ga.descripcion_gar");
		$dbCitas->select("CONCAT(COALESCE(cl.nombres,''), ' ', COALESCE(cl.apellido_paterno,''), ' ', COALESCE(cl.apellido_materno,'')) AS cliente",FALSE);
    $dbCitas->from('cita ci');
		$dbCitas->join('cliente cl', 'ci.idcliente = cl.idcliente');
		$dbCitas->join('garante ga', 'ci.idgarante = ga.idgarante');
		if($paramDatos['estado']['id'] === 'ALL'){
			$dbCitas->where_in('ci.estado_cita', array(1, 0));
		}
		if($paramDatos['estado']['id'] === '1'){
			$dbCitas->where_in('ci.estado_cita', array(1));
		}
		if($paramDatos['estado']['id'] === '0'){
			$dbCitas->where_in('ci.estado_cita', array(0));
		}
		
		$dbCitas->where_in('cl.estado_pac', array(1));
		$dbCitas->where('ci.fecha_cita BETWEEN '. $dbCitas->escape( darFormatoYMD($paramDatos['desde']).' '.$paramDatos['desdeHora'].':'.$paramDatos['desdeMinuto']) .' AND ' 
			. $dbCitas->escape( darFormatoYMD($paramDatos['hasta']).' '.$paramDatos['hastaHora'].':'.$paramDatos['hastaMinuto']));
		if( isset($paramPaginate['search'] ) && $paramPaginate['search'] ){
			foreach ($paramPaginate['searchColumn'] as $key => $value) {
				if(! empty($value)){
					$dbCitas->like($key ,strtoupper_total($value) ,FALSE);
				}
			}
		}
		if( $paramPaginate['sortName'] ){
			$dbCitas->order_by($paramPaginate['sortName'], $paramPaginate['sort']);
		}
		if( $paramPaginate['firstRow'] || $paramPaginate['pageSize'] ){
			$dbCitas->limit($paramPaginate['pageSize'],$paramPaginate['firstRow'] );
		}
		return $dbCitas->get()->result_array();
	}

	public function m_count_citas($paramPaginate, $paramDatos){
		$dbCitas = $this->load->database('citas', TRUE);
		$dbCitas->select('COUNT(*) AS contador');
		$dbCitas->from('cita ci');
		$dbCitas->join('cliente cl', 'ci.idcliente = cl.idcliente');
		$dbCitas->join('garante ga', 'ci.idgarante = ga.idgarante');
		if($paramDatos['estado']['id'] === 'ALL'){
			$dbCitas->where_in('ci.estado_cita', array(1, 0));
		}
		if($paramDatos['estado']['id'] === '1'){
			$dbCitas->where_in('ci.estado_cita', array(1));
		}
		if($paramDatos['estado']['id'] === '0'){
			$dbCitas->where_in('ci.estado_cita', array(0));
		}
		$dbCitas->where_in('cl.estado_pac', array(1));
		$dbCitas->where('ci.fecha_cita BETWEEN '. $dbCitas->escape( darFormatoYMD($paramDatos['desde']).' '.$paramDatos['desdeHora'].':'.$paramDatos['desdeMinuto']) .' AND ' 
			. $dbCitas->escape( darFormatoYMD($paramDatos['hasta']).' '.$paramDatos['hastaHora'].':'.$paramDatos['hastaMinuto']));
		if( isset($paramPaginate['search'] ) && $paramPaginate['search'] ){
			foreach ($paramPaginate['searchColumn'] as $key => $value) {
				if(! empty($value)){
					$dbCitas->like($key ,strtoupper_total($value) ,FALSE);
				}
			}
		}
		$fData = $dbCitas->get()->row_array();
		return $fData;
	}
}
?>