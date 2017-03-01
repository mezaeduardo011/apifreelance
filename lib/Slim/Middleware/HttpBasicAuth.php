<?php

/** 
 * Archivo encargado de gestionar las peticiones por autenticacion por HttpBasics
 * principal cuando carga el sistema inicialmente se conecta para autenticar basic
 */

// Archivo de Conexion a la base de datos
include_once __DIR__.'/../../DriverDB/ConexionDataBase.class.php';


class HttpBasicAuth extends \Slim\Middleware
{
    /**
     * @var string
     */
    protected $realm;
    /**
     * Constructor
     *
     * @param   string  $realm      The HTTP Authentication realm
     */
    public function __construct()
    {
        // Arhivo de configuracion para los procesos
        $strFileName = __DIR__ . "/../../../config/app.ini";
        file_exists($strFileName) ? $objFopen = parse_ini_file($strFileName, true) : die("File nor Found " . $strFileName);
  
        $this->realm = $objFopen['default']['nameSite'];
    }
    /**
     * Deny Access
     *
     */   
    public function deny_access() {
        $res = $this->app->response();
        $res->status(401);
        $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));       
    }

    /**
     * Authenticate
     *
     * @param   string  $username   The HTTP Authentication username
     * @param   string  $password   The HTTP Authentication password    
     *
     */
    public function authenticate($username, $password) {

        if(isset($username) && isset($password)) {
            //$password = md5($password);
            // Check database here with $username and $password 
            // Conexion contra base de datos
            try {
                $connec = new Database('catatumbo');
            } catch(PDOException $e) {
                die(print_r(json_encode(array('error' => 3, 'message'=> "Failed to connect to database ".$e->getMessage()))));

            } 
            // Query para extraer todas las actividades registradas  
            $sql = "SELECT id, tocken, secret, name, redirect_url FROM outh WHERE tocken='".$username."' AND secret='".$password."'";
            $query = $connec->prepare($sql);
            $query->execute();
            $connec->close_con();

            /** Verifico si hay mas de un registro */
            $rows = count($query->fetchAll(PDO::FETCH_ASSOC));

            if($rows==1){
                return true;
            }else{
                return false;
            }
        }
    }
    /**
     * Call
     *
     * This method will check the HTTP request headers for previous authentication. If
     * the request has already authenticated, the next middleware is called. Otherwise,
     * a 401 Authentication Required response is returned to the client.
     */
    public function call()
    {
        $req = $this->app->request();
        $res = $this->app->response();
        $authUser = $req->headers('PHP_AUTH_USER');
        $authPass = $req->headers('PHP_AUTH_PW');

        if ($this->authenticate($authUser, $authPass)) {
            $this->next->call();
        } else {
            $this->deny_access();
        }
    }
}