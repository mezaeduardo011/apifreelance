<?php
require_once(__DIR__.'/../../lib/Common/Common.class.php');



$config = parse_ini_file(__DIR__.'/../../config/app.ini', true);
require __DIR__ . '/../../vendor/autoload.php';
$settings = [
'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
        'template_path' => __DIR__ . '/../../templates/',
        ],
        ],
        ];
        $app = new \Slim\App($settings);
        require_once(__DIR__.'/../../lib/Common/newComponts.php'); 
        require_once(__DIR__.'/../../lib/Common/dependencias.php'); 

        $common = new Common();



/*
variables for messages
0 Procesado exitosamente
1 Fallo en visualizar los registros
2 Fallo en efectuar el registro
3 No se puede conectar con la base de datos
404 ERROR 404
*/

defined('MESSAGE_NOTIFIC') != 1 ? define('MESSAGE_NOTIFIC', 'Success: registration was successful, I was sent an email to activate your user account.') : NULL;
defined('MESSAGE_INSETSU') != 1 ? define('MESSAGE_INSETSU', 'Éxito: Se ha Registrado correctamente.') : NULL;
defined('MESSAGE_ERRORSS') != 1 ? define('MESSAGE_ERRORSS', 'Warning: Error while processing the data.') : NULL;
defined('MESSAGE_INFORSS') != 1 ? define('MESSAGE_INFORSS', 'Notification: Data already recorded.') : NULL;
defined('MESSAGE_SIGNINS') != 1 ? define('MESSAGE_SIGNINS', 'Error: username and password is not registered.') : NULL;
defined('MESSAGE_ACTIVAT') != 1 ? define('MESSAGE_ACTIVAT', 'Sccessful: account activated successfully.') : NULL;
defined('MESSAGE_ACTIVNO') != 1 ? define('MESSAGE_ACTIVNO', 'Error: not account activated .') : NULL;
defined('MESSAGE_LIST_00') != 1 ? define('MESSAGE_LIST_00', 'Notification: Sin registro asociado') : NULL;

try {

	$connec = new Database('catatumbo');
	$module=$common->getFileExecute(__DIR__);


/*$app->notFound(function () use ($app) {
	$returnObjects = array('error' => 404, 'message'=> 'ERROR 404, Pagina no encontrada (Page Not Found)' );
    print_r(json_encode($returnObjects));
});*/

$app->get('/login', function($request, $response, $args) use ($app,$config,$common,$connec){
	$mensaje = '';
	return $this->renderer->render($response,'viewLogin.php', array('system'=>$config['default'],'common'=>$common,'mensaje'=>$mensaje));
});

$app->post('/proceforms', function($request, $response, $args) use ($app,$config,$common,$connec){
	$conn = end($connec);
	if($request->isPost())
	{
		$post = (object)$request->getParams();
		if(isset($post->user) && isset($post->passwort))
		{
			$app->redirect('login');
		}else{
			// Capturando los datos para el registro de los usuarios
			$user=$request->getParam('user');
			$password=$request->getParam('password');


        // Query para extraer todas las metas de la pagina web  
			$sql = "SELECT usuario, clave FROM usuarios WHERE usuario='".$user."' AND clave='".$password."' ";
			$query = $conn->prepare($sql);
			$result=$query->execute();
			/** Verifico si hay mas de un registro */
			$rows = count($query->fetchAll(PDO::FETCH_ASSOC));
			$connec->close_con();
			if($rows==1){
				return $response->withStatus(302)->withHeader('Location', '/admin');
			}else{
				$mensaje='Usuario y clave incorrecto intente nuevemente.';
				return $this->renderer->render($response,'viewLogin.php', array('system'=>$config['default'],'common'=>$common,'mensaje'=>$mensaje));

			}
			
		}
	}
});


$app->get('/admin', function ($request, $response, $args) use ($app,$config,$common,$connec) {
	return $this->renderer->render($response, 'viewBase.php', array('system'=>$config['default'],'common'=>$common));
});

$app->get('/logout', function () {
    //Remove auth cookie and redirect to login page
});

} catch(PDOException $e) {
	die(print_r(json_encode(array('error' => 3, 'message'=> "Failed to connect to database ".$e->getMessage()))));
}
