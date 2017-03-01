<?php
// Conexion contra base de datos
try {
    $connec = new Database('catatumbo');


} catch(PDOException $e) {
    die(print_r(json_encode(array('error' => 3, 'message'=> "Failed to connect to database ".$e->getMessage()))));
}
// Se encarga de extraer el nombre de la carpeta donde se instanciará
$module=$common->getFileExecute(__DIR__);


//uthUser = $req->headers('PHP_AUTH_USER');

/**
* @apiService {get} #module/getAll/{id}, Extraer un listado con las etiquetas metas del sitio web
* @apiVersion 0.1.0
* @apiName getAll
* @apiGroup #group
*
* @apiParam {Integer} id, Identificador referencial FK, debe ingresar cero (0)
*
* @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
* @apiRSuccess {Varchar} message, Si existen registro con la condición no trae mensaje, de lo contrario debe mostrar #MESSAGE_LIST_00  
* @apiRSuccess {Json} data, debe extraer un json con los usuarios que cumplen las condición
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
    $app -> get('/'.$module.'/getAll/{id}', function($request, $response, $args) use ($connec){
       
       $conn = end($connec);

     // Query para extraer todas las metas de la pagina web  
        $sql = "SELECT *  FROM metas WHERE estatus = 1 ";
        $query = $conn->prepare($sql);
        $result=$query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        $connec->close_con();

        /** Verifico si hay mas de un registro */
        $rows = count($data); 

        /** Verifico si el registro fue procesado exitosamente */
        if($rows > 0){
            $returnObjects = array('error' => 0, 'message'=>'', 'data'=>$data);  
        }else{
            $returnObjects = array('error' => 1, 'message'=> MESSAGE_LIST_00 );
        }
        print_r(json_encode($returnObjects));
    });

