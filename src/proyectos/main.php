<?php
require_once('../recursos/manageDataService.php');
// Conexion contra base de datos
try {
    $connec = new Database('catatumbo');
    $sd =  new manageDataService;
} catch(PDOException $e) {
    die(print_r(json_encode(array('error' => 3, 'message'=> "Failed to connect to database ".$e->getMessage()))));
}
// Se encarga de extraer el nombre de la carpeta donde se instanciará
$module=$common->getFileExecute(__DIR__);


//uthUser = $req->headers('PHP_AUTH_USER');

/**
* @apiService {get} #module/getUsuario/{id}, Obtiene un usuario mediante el id.
* @apiVersion 0.1.0
* @apiName getUsuario
* @apiGroup #group
*
* @apiParam {Integer} id, Identificador de registro, debe ingresar id.
*
* @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
* @apiRSuccess {Varchar} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00  
* @apiRSuccess {Json} data, debe extraer un json con el usuario
*
* @apiRSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*     {
*       "error": 0,
*       "message": '' OR '#MESSAGE_LIST_00',
*       "data": [{}]
*     }
*
* @apiRError {Integer} error,  Código 1 no todo ha ido bien.
* @apiRError {Varchar} message,  #MESSAGE_ERRORSS
*
* @apiRErrorExample Error-Response:
*     HTTP/1.1 200 OK
*     {
*       "error": 0,
*       "message":'#MESSAGE_ERRORSS',
*       "data":      
*     }
*/

// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> get('/'.$module.'/getProyectos/{id}', function($request, $response, $args) use ($connec,$sd){
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');
        $conn = end($connec);
        $sql = "select * from view_proyectos_clientes WHERE id_cliente = '$id'";
        $sd->getData($conn,$sql,$connec);
    });



// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> get('/'.$module.'/getProyectosXPostulaciones/{id}', function($request, $response, $args) use ($connec,$sd){
        $conn = end($connec);
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');        
        $sql = "SELECT * from proyecto WHERE id NOT IN (select id_proyecto from postulaciones where id_freelance = $id)";
        $sd->getData($conn,$sql,$connec);
    });


// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> get('/'.$module.'/getPostulacionesxProyecto/{id}', function($request, $response, $args) use ($connec,$sd){
        $conn = end($connec);
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');        
        $sql = "SELECT * from postulaciones WHERE id IN (select id from postulaciones where id_proyecto= $id)";
        $sd->getData($conn,$sql,$connec);
    });

// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> get('/'.$module.'/getPostulaciones/{id}', function($request, $response, $args) use ($connec,$sd){
        $conn = end($connec);
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');        
        $sql = "SELECT * from postulaciones WHERE id_freelance = $id";
        $sd->getData($conn,$sql,$connec);
    });
    
// """ Method encargado de enviar las notificacione de mensaje de textos """
    $app -> get('/'.$module.'/login/{correo}/{contrasena}', function($request, $response, $args) use ($connec,$sd){
        $conn = end($connec);
        $route = $request->getAttribute('route');
        $correo = $route->getArgument('correo');  
        $contrasena = $route->getArgument('contrasena');  
        $sql = "SELECT u.* ,p.perfil from usuarios as u
                INNER JOIN perfil as p on p.id = u.id_perfil
                WHERE correo = '".$correo."' AND contrasena = '".$contrasena."'  ";
        $sd->getData($conn,$sql,$connec);
    });
    
    /**
     * @apiService {post} #module/insertUsuario, Registrar un usuario.
     * @apiVersion 2.1.0
     * @apiName insertUsuario
     * @apiGroup #group
     * 
     * @apiParam {varchar(45)} correo, correo para uso de usuario en el sistema.
     * @apiParam {Varchar(20)} contraseña, contraseña del usuario.
     * @apiParam {Varchar(20)} confirmacion_contraseña,confirmacion de contraseña ingresada.
     * @apiParam {integer} id_perfil, id 1 para ingresar un cliente. id 2 para un freelance.
     *
     * @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 0,
     *       "code": '#MESSAGE_CODE',
     *       "message": '#MESSAGE_INSETSU'
     *     }
     *
     * @apiRError {Integer} error,  Código 1 no todo ha ido bien.
     * @apiRError {Varchar} message,  #MESSAGE_ERRORSS
     *
     * @apiRErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 1,
     *       "message":'#MESSAGE_ERRORSS'       
     *     }
     */
    $app -> post('/'.$module.'/insertProyecto', function($request, $response, $args) use ($connec,$common,$sd){

        $conn = end($connec);
        $reemplazo = array("habilidades" => json_encode($request->getParsedBody()['habilidades']));
        $reemplazo2 = array("herramientas" => json_encode($request->getParsedBody()['herramientas']));
        $params = array_replace($request->getParsedBody(),$reemplazo,$reemplazo2);
        $sd->setDataParam($params);
        $sd->saveAll($conn,$connec,"proyecto");
    });



    /**
     * @apiService {post} #module/insertUsuario, Registrar un usuario.
     * @apiVersion 2.1.0
     * @apiName insertUsuario
     * @apiGroup #group
     * 
     * @apiParam {varchar(45)} correo, correo para uso de usuario en el sistema.
     * @apiParam {Varchar(20)} contraseña, contraseña del usuario.
     * @apiParam {Varchar(20)} confirmacion_contraseña,confirmacion de contraseña ingresada.
     * @apiParam {integer} id_perfil, id 1 para ingresar un cliente. id 2 para un freelance.
     *
     * @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 0,
     *       "code": '#MESSAGE_CODE',
     *       "message": '#MESSAGE_INSETSU'
     *     }
     *
     * @apiRError {Integer} error,  Código 1 no todo ha ido bien.
     * @apiRError {Varchar} message,  #MESSAGE_ERRORSS
     *
     * @apiRErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 1,
     *       "message":'#MESSAGE_ERRORSS'       
     *     }
     */
    $app -> post('/'.$module.'/insertPostulacion', function($request, $response, $args) use ($connec,$common,$sd){

        $conn = end($connec);
        $reemplazo = array("recursos" => json_encode($request->getParsedBody()['recursos']));
        $params = array_replace($request->getParsedBody(),$reemplazo);
        $sd->setDataParam($params);
        $sd->saveAll($conn,$connec,"postulaciones");
    });
    /**
     * @apiService {post} #module/updateUsuario, Actualizar datos de usuario.
     * @apiVersion 2.1.0
     * @apiName insertUsuario
     * @apiGroup #group
     *
     * @apiParam {integer} id, id del usuario al que se actualizaran los datos.
     * @apiParam {varchar(45)} correo, correo para uso de usuario en el sistema.
     * @apiParam {Varchar(20)} contraseña, contraseña del usuario.
     * @apiParam {Varchar(20)} confirmacion_contraseña,confirmacion de contraseña ingresada.
     * @apiParam {integer} id_perfil, id 1 para ingresar un cliente. id 2 para un freelance.
     *
     * @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 0,
     *       "code": '#MESSAGE_CODE',
     *       "message": '#MESSAGE_INSETSU'
     *     }
     *
     * @apiRError {Integer} error,  Código 1 no todo ha ido bien.
     * @apiRError {Varchar} message,  #MESSAGE_ERRORSS
     *
     * @apiRErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 1,
     *       "message":'#MESSAGE_ERRORSS'       
     *     }
     */
    $app -> post('/'.$module.'/updateUsuario', function($request, $response, $args) use ($connec,$common,$sd){
        $conn = end($connec);

        $sd->setDataParam($request->getParsedBody());

        if(contrasena() == confirmacion_contrasena()){
            $sd->updateAll($conn,$connec,"usuarios");
        }else{
            $sd->showMessage("Las contraseñas ingresadas no coinciden");
        } 

    });


    /**
     * @apiService {post} #module/deleteUsuario, Eliminar a un usuario.
     * @apiVersion 2.1.0
     * @apiName insertUsuario
     * @apiGroup #group
     *
     * @apiParam {integer} id, id del usuario que se eliminara.
     *
     * @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 0,
     *       "code": '#MESSAGE_CODE',
     *       "message": '#MESSAGE_INSETSU'
     *     }
     *
     * @apiRError {Integer} error,  Código 1 no todo ha ido bien.
     * @apiRError {Varchar} message,  #MESSAGE_ERRORSS
     *
     * @apiRErrorExample Error-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 1,
     *       "message":'#MESSAGE_ERRORSS'       
     *     }
     */
    $app -> post('/'.$module.'/deleteUsuario', function($request, $response, $args) use ($connec,$common){
        $conn = end($connec);
        $id = $request->getParam('id');
        $sql = "DELETE FROM usuarios WHERE id = $id";
        manageDataService::sendData($conn,$sql,$connec);

    });    