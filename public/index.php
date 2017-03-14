<?php
// Desactivar toda notificación de error
//error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");
date_default_timezone_set('America/La_Paz');
include_once (__DIR__."/../lib/DriverDB/ConexionDataBase.class.php");

$path = __DIR__.'/../src/';
include_once ($path."common/main.php");
$config = parse_ini_file('../config/app.ini', true);

$dir = opendir($path);
$data[]=null;
// Leer todos los ficheros de la carpeta
while ($elemento = readdir($dir)){
        // Tratamos los elementos . y .. que tienen todas las carpetas
	if( $elemento != "." && $elemento != ".." && $elemento != "common" && $elemento != "authentication"){
        // Verificar si es una Carpeta
		if( is_dir($path.$elemento) ){
            // Muestro la carpeta
			$file = $path."".$elemento."/main.php";
			include_once($file);
			$getFile=file_get_contents($file);
			$getItem = $common->multiExplode(array("/**",'*/'),$getFile,$elemento,$config['default']['apiRestSi']);
			//$common->printAll($getItem);

			if(!is_null($getItem)){$data[$elemento]=$getItem;}
			
		} 
	}
}

$app->get('/', function($request, $response, $args) use ($app,$config,$common){
   
    
    return $this->renderer->render($response,'viewHome.php', array('system'=>$config['default'],'common'=>$common));
});


$app->get('/documents', function($request, $response, $args) use ($app,$data,$config,$common){
    return $this->renderer->render($response,'viewDocuments.php', array('apiDoc' => $data,'system'=>$config['default'],'common'=>$common));
});
$app -> run();
?>
