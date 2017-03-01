<?php
/**
* 
*/
require_once __DIR__.'/Random.php';
class Common
{
	public $timestamp, $ruta, $resul;

	public function __construct(){
		header('Content-type:application/json;charset=utf-8');
		$this->timestamp; $this->ruta; $this->resul;
	}
	
	public function printAll($data){
		echo '<pre>';print_r($data);die();
	}

	public function getFileExecute($dirExecute){
		$this->result=explode('/', $dirExecute);
		$this->ruta=end($this->result);
		return $this->ruta;
	}

	public function utf8enc($array) {
		if (!is_array($array)) return;
		$helper = array();
		foreach ($array as $key => $value) $helper[utf8_encode($key)] = is_array($value) ? self::utf8enc($value) : utf8_encode($value);
		return $helper;
	}

	public function utf8dec($array) {
		if (!is_array($array)) return;
		$helper = array();
		foreach ($array as $key => $value) $helper[utf8_decode($key)] = is_array($value) ? self::utf8enc($value) : utf8_decode($value);
		return $helper;
	}

	
	public function dataNow(){
		// Fecha y hora
		return $this->timestamp = date('Y-m-d H:i:s');
	}

	/**
	 * Method encargado de instanciar una clase random para generar un codigo aleatorio de string
	 * la posibilidad que se repita en las iteracion es de 0% en una prueba de 50,546 registros masivos  
	 * @param $num integer cantidad de resultado que quieres mostrar, ejemplo si mandas 1 te devolvera 2
	 * return string codido generado
	 */
	public function randomString($num){
		$rand = new Random();
		$resul=$rand->alfaNumerico($num);
		return $resul;
	}

	public function multiExplode($delimiters,$string,$nameMod,$apiRestSi) {
		$variable = explode($delimiters[0],$string);
		foreach ($variable as $key => $value) {
			$variable = explode($delimiters[1],$value);
			foreach ($variable as $key => $value) {
	    	//echo $value;
				if(strpos($value,'@apiService')){
					$repTag = array('#group','#module','*','#MESSAGE_NOTIFIC', '#MESSAGE_INSETSU', '#MESSAGE_ERRORSS', '#MESSAGE_INFORSS', '#MESSAGE_SIGNINS', '#MESSAGE_ACTIVAT', '#MESSAGE_ACTIVNO', '#MESSAGE_LIST_00');
					$repRea = array($nameMod,$apiRestSi.''.$nameMod,'',MESSAGE_NOTIFIC, MESSAGE_INSETSU, MESSAGE_ERRORSS, MESSAGE_INFORSS, MESSAGE_SIGNINS, MESSAGE_ACTIVAT, MESSAGE_ACTIVNO, MESSAGE_LIST_00);
					$a=str_replace($repTag,$repRea,$value);

					@$ary[]=$a;
				}
			}
		}
		return  @$ary;
	}

	public function getTabsAnnotations($bloque){
		return $return = explode('@', $bloque);
	}

	public function getTabsService($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'Service')){
				$p1=explode(',', str_replace('apiService','',$value));
				$p2=explode(' ', trim($p1[0]));
				$dataTabs['method'] = $p2[0];
				$dataTabs['metsin'] = str_replace(array('{','}'),array('',''),$p2[0]);
				$dataTabs['servic'] = $p2[1];
				$dataTabs['descri'] = $p1[1];
			}
		}
		return (object)$dataTabs;
	}

	public function getTabsVersion($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'Version')){
				$p1=explode(' ', str_replace('apiVersion','',$value));
			}
		}
		$dataTabs = $p1[1];
		return $dataTabs;
	}

	public function getTabsParameter($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'Param')){
				$p1=explode(',', str_replace('apiParam','',$value));
				$p2=explode(' ', trim($p1[0]));
				$param = $p2[1];
				$dataTabs[$param]=array('type'=>$p2[0], 'param'=>$param,'detalles'=>$p1[1]);
			}
		}
		return (object)$dataTabs;
	}

	public function getTabsParameterSuccess($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'RSuccess') and !stripos($value, 'RSuccessExample')){
				$p1=explode(',', str_replace('apiRSuccess','',$value));
				$p2=explode(' ', trim($p1[0]));
				$param = $p2[1];
				$dataTabs[$param]=array('type'=>$p2[0], 'param'=>$param,'detalles'=>$p1[1]);
			}
		}
		return (object)$dataTabs;
	}

	public function getTabsParameterSuccessResponse($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'SuccessExample')){
				$dataTabs['response']=str_replace(array('apiSuccessExample','Success-Response:'),array('',''),$value);
			}
		}
		return (object)$dataTabs;
	}

	public function getTabsParameterError($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'RError') and !stripos($value, 'RErrorExample')){
				$p1=explode(',', str_replace('apiRError','',$value));
				$p2=explode(' ', trim($p1[0]));
				$param = $p2[1];
				$dataTabs[$param]=array('type'=>$p2[0], 'param'=>$param,'detalles'=>$p1[1]);
			}
		}
		return (object)$dataTabs;
	}

	public function getTabsParameterErrorResponse($domain){
		foreach ($domain as $key => $value) {
			if(stripos($value,'RErrorExample')){
				$dataTabs['response']=str_replace(array('apiRErrorExample','Error-Response:'),array('',''),$value);
			}
		}
		return (object)$dataTabs;
	}


	/**
	 *  Method encargado de gestionar con la api restfull
	 *  @dataBasic Encargada de tener la configuracion donde se encuentra el api rest + usuario y clave
	 *  @data Encargada de contener un arreglo de los datos que seran enviado al api rest
	 */
	public function clientRestBase($dataBasic, $data){
		/*  @$dataBasic
		 	[apRest] => http://192.168.0.103:8084/rumberos/register
		    [tocken] => 123456
		    [secret] => 123456
		    [method] => POST
		*/

		    $dataJson = '';
		    // Validar si el ApiRest esta activo
		    $connec = self::validateRowsForm('URL',$dataBasic['apRest']);
		    if(!$connec){
		    	$dataJson['error'] = 1;
		    	$dataJson['message'] = 'Error, Al establecer conexion al APiRestFull del CUT.';
		    	return json_encode($dataJson); 
		    }
		    $handle = curl_init();

		    curl_setopt($handle, CURLOPT_URL, $dataBasic['apRest']);
		    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		    //curl_setopt($handle, CURLOPT_USERPWD, $dataBasic['tocken'].":".$dataBasic['secret']);
		    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

		    switch($dataBasic['method'])
		    {

		    	case 'GET':
		    	break;

		    	case 'POST':
		    	curl_setopt($handle, CURLOPT_POST, true);
		    	curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
		    	break;

		    	case 'PUT':
		    	curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
		    	curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
		    	break;

		    	case 'DELETE':
		    	curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
		    	break;
		    }

		    $response = curl_exec($handle);
		    curl_close($handle);

		    return $response;
		}


		public function validateRowsForm($type, $data){
			switch($type)
			{
		    	case 'REQ': // Dato requ
		    	$retorno=($data == '')? FALSE: TRUE;
		    	break;

		    	case 'NUM': // Solo númericos
		    	$retorno=(filter_var($data, FILTER_VALIDATE_INT) === FALSE)? FALSE: TRUE;
		    	break;

		    	case 'LET': // Solo letras
		    	$retorno=(filter_var($data, FILTER_VALIDATE_INT) === FALSE)? FALSE: TRUE;

		    	break;

		    	case 'STR': // Solo String alfanumerico
		    	$retorno=(filter_var($data, FILTER_SANITIZE_STRING) === FALSE)? FALSE: TRUE;

		    	break;

		    	case 'EMA': // Solo correos electrinico
		    	$retorno=(filter_var($data, FILTER_VALIDATE_EMAIL) === FALSE)? FALSE: TRUE;
		    	break;

		    	case 'URL': // Solo direcciones de internet
		    	$retorno=(filter_var($data, FILTER_VALIDATE_URL) === FALSE)? FALSE: TRUE;
		    	break;

		    	case 'BOO': // Identificar si el registro es booleano
		    	$retorno=(filter_var($data, FILTER_VALIDATE_BOOLEAN) === FALSE)? FALSE: TRUE;
		    	break;
		    	case 'URLACT':
		    	$retorno=(self::file_get_contents_curl($data))? TRUE : FALSE;
		    	break;
		    }
		    return $retorno;
		}

		/**
		* Funcion encargada de validar si un enlace es valido pero 
		* buscando la funcion en otro servidor, cuando no esta activo
		* opciones de file_get_contents
		* @param $url string Enlace que deseas validar
		* @return $return Json metatags, title, validate
		*/
		public function file_get_contents_curl($url){
			// Web donde ira a buscar la validacion
			$apiRest='http://dategeekgroup.com.ve/';
        	// Agregar el method POST porque es dato de registro
			$dataBasic = array('method' => 'POST');

			$dataPost = array(
				'typ'=>'validateUrl',
				'url'=>$url
				);
        // Fucionar las rutas para poder enviarla como config al apiRest
			$dataBasic = array_merge(array('apRest' => $apiRest.'validateUrl.php'), $dataBasic);

        // Consultar api rest encargado de gestionar con la capa de base de datos
			$return = self::clientRestBase($dataBasic, $dataPost);
			return $return;
		}

				/**
		* Funcion encargada de validar si un enlace es valido pero buscando la funcion local
		* @param $url string Enlace que deseas validar
		* @return $return Json metatags, title, validate
		*/
		public function file_get_contents_local($url){
			// Procedimiento para validar si la url es valida y esta disponible
			$a=@fopen($url,"r");;
			if ($a){
				$validate = true;
				// Procedimiento para extraer los metatags de la url enviada
				$b=get_meta_tags($url);
				$responce['metatags'] = $b; 
				// Extraer el Titulo de la pagina
				$str = file_get_contents($url);
				if(strlen($str)>0){
			        preg_match("@<title>(.*)</title>@",$str,$title);
			        $responce['title'] = $title[1];
			    }
			}else{
				$validate = false;
			}
			$responce['validate'] = $validate; 
			return $responce;
		}


	}
	?>