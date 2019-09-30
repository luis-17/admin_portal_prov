<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Acceso extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper(array('security','config'));
		$this->load->model(array('model_acceso','model_colaborador','model_configuracion'));
		//cache
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0"); 
		$this->output->set_header("Pragma: no-cache");
		date_default_timezone_set("America/Lima");		
	}

	public function index(){ 
		$allInputs = json_decode(trim(file_get_contents('php://input')),true);
		$arrData['flag'] = 0;
    	$arrData['message'] = 'Rellene todos los campos.';
		if(!empty($allInputs['usuario']) && !empty($allInputs['password']) ){ 
			$loggedUser = $this->model_acceso->m_logging_user($allInputs);
			if( isset($loggedUser['logged']) && $loggedUser['logged'] > 0 ){				
    			if($loggedUser['estado_us'] == 1){
					$arrData['flag'] = 1;
					$arrPerfilUsuario = array();
					// $arrPerfilUsuario = $this->model_colaborador->m_cargar_perfil($loggedUser['idusuario']);
					$arrPerfilUsuario['username'] = $loggedUser['username'];
					$arrPerfilUsuario['idusuario'] = $loggedUser['idusuario'];
					$arrPerfilUsuario['nombre_foto'] = 'sin-imagen.png';
					// GUARDAMOS EN EL LOG DE LOGEO LA SESION INICIADA. 
					//$this->model_acceso->m_registrar_log_sesion($arrPerfilUsuario); 

					if( empty($arrPerfilUsuario['idusuario']) ){ 
						$arrData['flag'] = 2;
						$arrData['message'] = 'Hay problemas con su cuenta. <br>Las posibles causas son: <br> - Su usuario ha sido deshabilitado';
					}else{
						// ACTUALIZAMOS EL ULTIMO LOGEO DEL USUARIO. 
						$this->model_acceso->m_actualizar_datos_usuario_ultima_sesion($arrPerfilUsuario);
						$arrData['message'] = 'Usuario inició sesión correctamente';
						if( isset($arrPerfilUsuario['idusuario']) ){ 
							$this->session->set_userdata('sess_fact_'.substr(base_url(),-20,7),$arrPerfilUsuario);
							
						}else{
							$arrData['flag'] = 0;
		    				$arrData['message'] = 'No se encontró los datos del usuario.';
						}
					}
				}elseif($loggedUser['estado_us'] == 2){
					$arrData['flag'] = 2;
					$arrData['message'] = 'Su cuenta se encuentra deshabilitada. Contactar con Sistemas';
				} 				
			}else{ 
    			$arrData['flag'] = 0;
    			$arrData['message'] = 'Usuario o contraseña inválida. Inténtelo nuevamente.';
    		}		
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function getSessionCI(){
		$arrData['flag'] = 0;
		$arrData['datos'] = array();
		if( $this->session->has_userdata( 'sess_fact_'.substr(base_url(),-20,7) ) && !empty($_SESSION['sess_fact_'.substr(base_url(),-20,7) ]['idusuario']) ){ 
			$arrData['flag'] = 1;
			$arrData['datos'] = $_SESSION['sess_fact_'.substr(base_url(),-20,7) ]; 
			// $arrConfig = obtener_parametros_configuracion(); 
			// $arrData['datos']['config'] = $arrConfig;
		} 
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}
	public function logoutSessionCI(){
		$this->session->unset_userdata('sess_fact_'.substr(base_url(),-20,7));
        //$this->cache->clean();
        $arrData['flag'] = 1;
		$arrData['datos'] = 'Cerró sesión correctamente.';
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($arrData));
	}

}