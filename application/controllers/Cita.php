<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cita extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('security','imagen_helper','otros_helper','fechas_helper'));
		$this->load->library(array('excel'));
    // $dbCitas = $this->load->database('citas', TRUE);
		$this->load->model(array('model_cita'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->sessionFactur = @$this->session->userdata('sess_fact_'.substr(base_url(),-20,7));
		date_default_timezone_set("America/Lima");
		//if(!@$this->user) redirect ('inicio/login');
		//$permisos = cargar_permisos_del_usuario($this->user->idusuario);
	}
	public function listar()
	{ 
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);
		$paramPaginate = $allInputs['paginate'];
		$paramDatos = $allInputs['datos'];
		$lista = $this->model_cita->m_cargar_citas($paramPaginate, $paramDatos);
		$fCount = $this->model_cita->m_count_citas($paramPaginate, $paramDatos);
		$arrListado = array();
		foreach ($lista as $row) { 
			$strDescripcion = 'ANULADO';
			$strClaseLabel = ' label-default';
			if($row['estado_cita'] === '1'){
				$strDescripcion = 'ACTIVO';
				$strClaseLabel = ' label-success';
			}
			array_push($arrListado,
				array(
					'idcita' => $row['idcita'],
					'fecha_anulacion' => $row['fecha_anulacion'],
					'fecha_registro' => $row['fecha_registro'],
					'fecha_cita' => darFormatoDMY($row['fecha_cita']),
					'hora' => darFormatoHora($row['hora_inicio']).' - '.darFormatoHora($row['hora_fin']),
					'hora_inicio' => darFormatoHora($row['hora_inicio']),
					'hora_fin' => darFormatoHora($row['hora_fin']),
					'medico' => $row['medico'],
					'especialidad' => $row['especialidad'],
					'idcitaspring' => $row['idcitaspring'],
					'cliente' => $row['cliente'],
					'nombres' => $row['nombres'],
					'apellido_paterno' => $row['apellido_paterno'],
					'apellido_materno' => $row['apellido_materno'],
					'tipo_documento' => $row['tipo_documento'],
					'numero_documento' => $row['numero_documento'],
					'correo' => $row['correo'],
					'telefono' => $row['telefono'],
					'idgarante' => $row['idgarante'],
					'descripcion_gar' => $row['descripcion_gar'],
					'estado_obj' => array(
						'claseLabel' => $strClaseLabel,
						'estado_cita' => $row['estado_cita'],
						'labelText'=> $strDescripcion
					)
				)
			);
		}
    $arrData['datos'] = $arrListado;
    $arrData['paginate']['totalRows'] = $fCount['contador'];
    $arrData['message'] = '';
    $arrData['flag'] = 1;
		if(empty($lista)){
			$arrData['flag'] = 0;
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($arrData));
	}
	public function listado_citas_excel()
	{
		$allInputs = json_decode(trim($this->input->raw_input_stream),true);

		// TRATAMIENTO DE DATOS //
			$lista = array();

			$paramPaginate = $allInputs['paginate'];
			$paramPaginate['firstRow'] = FALSE;
			$paramPaginate['pageSize'] = FALSE;
			$paramDatos = $allInputs['filtro'];
			$nombre_reporte = 'citas_online';
			$lista = $this->model_cita->m_cargar_citas($paramPaginate,$paramDatos);

			$total = 0;
			$arrListado = array();
			$i = 1;
			foreach ($lista as $row) {
				if ( $row['estado_cita'] == '1' ){
					$estado = 'ACTIVO';
				}elseif ( $row['estado_cita'] == '0' ){
					$estado = 'ANULADO';
				}else {
					$estado = '';
				}
				array_push($arrListado,
					array(
						$i++,
						$row['idcita'],
						$row['idcitaspring'],
						$row['fecha_registro'],
						darFormatoDMY($row['fecha_cita']),
						darFormatoHora($row['hora_inicio']).' - '.darFormatoHora($row['hora_fin']),
						$row['tipo_documento'],
						$row['numero_documento'],
						$row['cliente'],
						$row['correo'],
						$row['telefono'],
						$row['medico'],
						$row['especialidad'],
						$row['descripcion_gar'],
						$estado
					)
				);
			}

			// SETEO DE VARIABLES
			$dataColumnsTP = array(
				array( 'col' => '#',                'ancho' =>  7, 	'align' => 'L' ),
				array( 'col' => 'COD CITA',			'ancho' => 10, 	'align' => 'C' ),
				array( 'col' => 'COD CITA SPRING',			'ancho' => 10, 	'align' => 'C' ),
				array( 'col' => "FECHA DE REGISTRO",	'ancho' => 24, 	'align' => 'C' ),
				array( 'col' => "FECHA DE CITA",	'ancho' => 12, 	'align' => 'C' ),
				array( 'col' => 'HORA CITA', 		'ancho' => 12, 	'align' => 'C' ),
				array( 'col' => 'TIPO DOCUMENTO',	'ancho' => 12, 	'align' => 'C' ),
				array( 'col' => 'Nº DOCUMENTO',		'ancho' => 15, 	'align' => 'C' ),
				array( 'col' => 'PACIENTE',			'ancho' => 60, 	'align' => 'L' ),
				array( 'col' => 'CORREO',			'ancho' => 60, 	'align' => 'L' ),
				array( 'col' => 'TELEFONO',			'ancho' => 45, 	'align' => 'L' ),
				array( 'col' => 'MEDICO',			'ancho' => 60, 	'align' => 'L' ),
				array( 'col' => 'ESPECIALIDAD',			'ancho' => 60, 	'align' => 'L' ),
				array( 'col' => 'GARANTE',			'ancho' => 15, 	'align' => 'L' ),
				array( 'col' => 'ESTADO',			'ancho' => 20, 	'align' => 'C' )
			);
			$titulo = 'LISTADO DE CITAS ONLINE';
			$nombre_hoja = 'Citas Online';

			$cantColumns = count($dataColumnsTP);
			$arrColumns = array();
			$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(2); // por defecto lo ponemos en 2 luego si se usa la columna se cambia
			$a = 'B'; // INICIO DE COLUMNA
			for ($x=0; $x < $cantColumns; $x++) {
				$arrColumns[] = $a++;
			}
			$endColum = end($arrColumns);
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle($nombre_hoja);
			$this->excel->getActiveSheet()->setShowGridlines(false);

		// ESTILOS
			$styleArrayTitle = array(
				'font'=>  array(
					'bold'  => false,
					'size'  => 18,
					'name'  => 'calibri',
					'color' => array('rgb' => 'FFFFFF')
			  	),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array( 'rgb' => '3A3838' )
				),
			);
			$styleArraySubTitle = array(
				'font'=>  array(
					'bold'  => false,
					'size'  => 12,
					'name'  => 'Microsoft Sans Serif',
					'color' => array('rgb' => 'FFFFFF')
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array( 'rgb' => '3A3838' )
				),
			);
			$styleArrayHeader = array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			  	),
				'font'=>  array(
					'bold'  => false,
					'size'  => 10,
					'name'  => 'calibri',
					'color' => array('rgb' => 'FFFFFF')
				),
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array( 'rgb' => '5B9BD5' )
				),
			);
		// TITULO
			$this->excel->getActiveSheet()->getCell($arrColumns[0].'1')->setValue($titulo);
			$this->excel->getActiveSheet()->getStyle($arrColumns[0].'1')->applyFromArray($styleArrayTitle);
			$this->excel->getActiveSheet()->mergeCells($arrColumns[0].'1:'. $endColum .'1');


			$currentCellEncabezado = 4; // donde inicia el encabezado del listado
			$fila_mes = $currentCellEncabezado - 1;
			$fila = $currentCellEncabezado + 1;
			$pieListado = $fila + count($arrListado);

		// ENCABEZADO DE LA LISTA
			$i=0;
			foreach ($dataColumnsTP as $key => $value) {
				$this->excel->getActiveSheet()->getColumnDimension($arrColumns[$i])->setWidth($value['ancho']);
				$this->excel->getActiveSheet()->getCell($arrColumns[$i].$currentCellEncabezado)->setValue($value['col']);
				if( $value['align'] == 'C' ){
					$this->excel->getActiveSheet()->getStyle($arrColumns[$i].$fila .':'.$arrColumns[$i].$pieListado)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}

				$i++;
			}
			$c1 = $i;
			$this->excel->getActiveSheet()->getStyle($arrColumns[0].$currentCellEncabezado.':'.$endColum.$currentCellEncabezado)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle($arrColumns[0].($currentCellEncabezado).':'.$endColum.($currentCellEncabezado))->applyFromArray($styleArrayHeader);
			$this->excel->getActiveSheet()->getRowDimension($currentCellEncabezado)->setRowHeight(45);
			$this->excel->getActiveSheet()->setAutoFilter($arrColumns[0].$currentCellEncabezado.':'.$endColum.$currentCellEncabezado);

		// LISTA
			$this->excel->getActiveSheet()->fromArray($arrListado, null, $arrColumns[0].$fila);
			$this->excel->getActiveSheet()->freezePane($arrColumns[0].$fila);


		$objWriter = new PHPExcel_Writer_Excel2007($this->excel);
		$time = date('YmdHis_His');
		$objWriter->save('assets/dinamic/excelTemporales/'. $nombre_reporte . '_' . $time.'.xlsx');

		$arrData = array(
		  'urlTempEXCEL'=> 'assets/dinamic/excelTemporales/'. $nombre_reporte . '_' . $time.'.xlsx',
		  'flag'=> 1
		);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($arrData));
	}
	public function ver_detalle_cita()
	{
		$this->load->view('cita/ver_cita');
	}
}
