<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
// Conexion contra base de datos
try {

    $connec = new Database('catatumbo');
    $module=$common->getFileExecute(__DIR__);
    

    /* Bloque relacionado a las Rutas Principales */

    /**
     * @apiService {post} #module/getURl, Buscar un enlace dentro del Registro
     * @apiVersion 0.1.0
     * @apiName getURl
     * @apiGroup #group
     *
     * @apiParam {Varchar(255)} url, Enlace clave a buscar.
     *
     * @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 0,
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
    $app -> post('/'.$module.'/getURl', function($request, $response, $args) use ($connec,$common, $app){
        $conn = end($connec);
        $url=$request->getParam('url');
        $sql = "SELECT * FROM rutas_principales WHERE url='$url'";
        $query = $conn->prepare($sql);
        $result=$query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        if(count($data)!=0){
            $returnObjects = array('error' => 0, 'message'=>'', 'data'=>$data);  
        }elseif(count($data)==0){
            $returnObjects = array('error' => 0, 'message'=> MESSAGE_LIST_00, 'data'=>$data );
        }else{
            $returnObjects = array('error' => 1, 'message'=> MESSAGE_ERRORSS );
        }
        print_r(json_encode($returnObjects));
    });

    /**
     * @apiService {post} #module/getCode, Buscar el tocken del enlace dentro del api
     * @apiVersion 0.2.1
     * @apiName getCode
     * @apiGroup #group
     *
     * @apiParam {Varchar(20)} code, Código del enlace a buscar.
     *
     * @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_EXITOSO
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 0,
     *       "message": '#MESSAGE_EXITOSO'
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
    $app -> post('/'.$module.'/getCode', function($request, $response, $args) use ($connec,$common, $app){
        $conn = end($connec);
        $code=$request->getParam('code');
        if(is_integer($code) and $code < 1165){$ord = 'OR id='.$code;}else{$ord='';}
        $sql = "SELECT * FROM rutas_principales WHERE code='$code' ".$ord;
        $query = $conn->prepare($sql);
        $result=$query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        if(count($data)!=0){
            $returnObjects = array('error' => 0, 'message'=>'', 'data'=>$data[0]);  
        }elseif(count($data)==0){
            $returnObjects = array('error' => 1, 'message'=> MESSAGE_LIST_00 );
        }else{
            $returnObjects = array('error' => 2, 'message'=> MESSAGE_ERRORSS );
        }
        print_r(json_encode($returnObjects));
    });

    /**
     * @apiService {get} #module/getCount/:all, Extraer la cantidad de registros de URL existente
     * @apiVersion 0.1.0
     * @apiName getCount
     * @apiGroup #group
     *
     * @apiParam {varchar(4)} all, Cantidad de registro a mistrar - 0=>Representa todo los registros.
     *
     * @apiRSuccess {Integer} error,  Código 0 conforme todo ha ido bien.
     * @apiRSuccess {Varchar} message,  #MESSAGE_INSETSU
     *
     * @apiRSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "error": 0,
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
    $app -> get('/'.$module.'/getCount/{all}', function($request, $response, $args) use ($connec,$common, $app){
        /* Validar si existe el usuario ingresado */
        $conn = end($connec);
        $sql = "SELECT count(id) as urlProcesados FROM rutas_principales";
        $query = $conn->prepare($sql);
        $result=$query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        $cont = end($data); 
        if(count($data)!=0){
            $returnObjects = array('error' => 0, 'message'=>'', 'data'=>$cont);  
        }elseif(count($data)==0){
            $returnObjects = array('error' => 0, 'message'=> MESSAGE_LIST_00, 'data'=>$cont);
        }else{
            $returnObjects = array('error' => 1, 'message'=> MESSAGE_ERRORSS );
        }
        print_r(json_encode($returnObjects));
    });

    /**
     * @apiService {post} #module/setRuta, Registro Principal de los enlaces
     * @apiVersion 2.1.0
     * @apiName setRuta
     * @apiGroup #group
     * 
     * @apiParam {Integer} socio, Socio de la plataforma autenticado - 0=>Anonimo.
     * @apiParam {Varchar(255)} url, Url de la página web Ingresada.
     * @apiParam {Integer} conector, Cliente que consume el servicio- 1=> WEB 2=>RESTFULL 3=>ANDROID.
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
    $app -> post('/'.$module.'/setRuta', function($request, $response, $args) use ($connec,$common){
        $conn = end($connec);

        // Datos enviados por post del cliente
        $socio=$request->getParam('socio');        // Socio en caso que este autenticado o sea mienbro
        $url=$request->getParam('url');            // Url del sitio
        $conector=$request->getParam('conector');  // Conector si es web, api, android, etc.

        // Consulta la validacion del url en el servidor
        $existe = $common->file_get_contents_local($url);
        $return = (object)$existe;
        
        //var_dump($return->validate);

        if($return->validate){

        // Extraer del return los datos del title y metatgs
            $title = $return->title;
            $tags = $return->metatags;


        // Generar el Codigo para de la clave del enlace
            do {
             // Genero el codigo
                $code = $common->randomString(5);
            // Proceso de validacion del code que no este repetido
                $sql = "SELECT * FROM rutas_principales WHERE code='$code'";
                $query = $conn->prepare($sql);
                $result = $query->execute();
                $data = $query->fetchAll(PDO::FETCH_ASSOC);

            } while (count($data)!=0);

        // Validar si es un socio o anonimo
            if($socio==0){
                $sql = "INSERT INTO rutas_principales(code,titulo,meta,url,conector) VALUES ('$code','$title,','".json_encode(@$tags)." ','$url',$conector);";
            }elseif($socio!=0){
                $sql = "INSERT INTO rutas_principales (code,socio_id,titulo,meta,url,conector) VALUES ('$code',$socio_id,'$title,','".json_encode(@$tags)." ', '$url',$conector);";
            } 


            $query = $conn->prepare($sql);
            $result=$query->execute();
            $connec->close_con();
        //var_dump($result); die();
        // Verifico si el registro fue procesado exitosamente 
            if($result){
                $returnObjects = array('error' => 0, 'code'=>$code, 'message'=>MESSAGE_INSETSU);  
            }else{
                $returnObjects = array('error' => 1, 'message'=> MESSAGE_ERRORSS );
            }
        }else{
            $returnObjects = array('error' => 1, 'message'=> MESSAGE_ERRORSS );
        }
        echo json_encode($returnObjects);

    });

   /**
     * @apiService {post} #module/setOneView, Registro de las visitas de los clientes
     * @apiVersion 2.1.0
     * @apiName setOneView
     * @apiGroup #group
     * 
     * @apiParam {String} code, Código del enlace a buscar.
     * @apiParam {Array} clienteView, El arreglo del resultado del $_SERVER del visitante.
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
   $app -> post('/'.$module.'/setOneView', function($request, $response, $args) use ($connec,$common){
    $conn = end($connec);

        // Datos enviados por post del cliente
        $code=$request->getParam('code');        // Código de la pagina web
        $server=$request->getParam('clienteView');  // Datos del SERVER del cliente Url del sitio
        
        // Datos del cluente Servidor
        $browser=$server['HTTP_USER_AGENT'];
        $movil=@$server['HTTP_X_REQUESTED_WITH'];
        $host=$server['REMOTE_ADDR'];
        $refere=@$server['HTTP_REFERER'];

        if(is_integer($code) and $code < 1165){$ord = 'OR id='.$code;}else{$ord='';}
        $sql = "SELECT * FROM rutas_principales WHERE code='$code' ";
        $query = $conn->prepare($sql);
        $result=$query->execute();
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($data)!=0){
            $urlId=$data[0]['id']; 
            $sql = "INSERT INTO registro_visitas (rutas_principales_id, navegador, movil, host, refere, created_at) VALUES ($urlId, '$browser', '$movil', '$host', '$refere', CURRENT_TIMESTAMP);";
            $query = $conn->prepare($sql);
            $result=$query->execute();
            $returnObjects = array('error' => 0, 'message'=>MESSAGE_INSETSU, 'data'=>0);  
        }else{
            $returnObjects = array('error' => 1, 'message'=> MESSAGE_ERRORSS );
        }
        
        echo json_encode($returnObjects);

    });





/* Bloque relacionado a los Friend tusers_friends*/


} catch(PDOException $e) {
    die(print_r(json_encode(array('error' => 3, 'message'=> "Failed to connect to database ".$e->getMessage()))));
}