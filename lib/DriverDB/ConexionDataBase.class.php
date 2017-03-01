<?php

/**
 * @propiedad: PROPIETARIO DEL CODIGO
 * @Autor: Gregorio Bolivar
 * @email: elalconxvii@gmail.com
 * @Fecha de Creacion: 02/11/2015
 * @Auditado por: Gregorio J Bolívar B
 * @Descripción: conexion de la base de datos
 * @package: ConexionDataBase.class
 * @version: 1.2
 */

class Database 
{  
    //creamos la conexión a la base de datos prueba
  public $conn;
  public function __construct()
  {
    try{
      self::constructConexion();
      try {
        $a = func_get_args();
        $index = $a[0]; 
        $config =& new ConfigDatabase();
        $v=$config->$index();
        $this->conn = new PDO($v->driv.':host='.$v->host.';dbname='.$v->dbna.';charset=utf8',''.$v->user.'',''.$v->pass.'');

      }catch(PDOException $e){
        header('Content-Type: text/html; charset=utf-8');
        die( "ERROR: " . $e->getMessage());
      }
    }catch(Exception $e){
      header('Content-Type: text/html; charset=utf-8');
      echo( "Excepción capturada: " . $e->getMessage());
      die();
      
    }
    return $this->conn;
  }

  public function constructConexion(){
      // Construimos automaticamente el archivo ConfigDatabaseTMP.php
    self::constructConfigDataBase();
      // Validamos que el archivo temporal creado anteriormente sea el mismo de la conexion de lo contrario procedemos a copiar el tmp
    self::validateFileIdentico();
    $file = __DIR__.'/ConfigDatabase.php';
    include_once $file;
  }
  public function constructConfigDataBase(){
    if (file_exists($file = __DIR__.'/../../config/databases.ini')) {
      $config = parse_ini_file(__DIR__.'/../../config/databases.ini', true);
    // Validamos que los dos archivos no existen

      $ar = fopen(__DIR__."/ConfigDatabaseTmp.php", "w+") or die("Problemas en la creaci&oacute;n del archivo  " . $file);
            // Inicio la escritura en el activo
    fputs($ar,'
        <?php
        /**
  * @propiedad: catatumbo
  * @Autor: Gregorio Bolivar
  * @email: elalconxvii@gmail.com
  * @Fecha de Creacion: ' . date('d/m/Y') . '
  * @Auditado por: Gregorio J Bolívar B
  * @Descripción: Generado por el generador de codigo de router de webStores
  * @package: ConfigDatabase.php
  * @version: 1.0
  * @webAutor: http://www.gregoriobolivar.com.ve/
  * @BlogAutor: http://gbbolivar.wordpress.com/
  */ ');
        fputs($ar, "\n");
        fputs($ar, "\n");
            // capturador del get que esta pasando por parametro
        fputs($ar, 'class ConfigDatabase');
        fputs($ar, "\n");
        fputs($ar, "{");
        fputs($ar, "\n");

        fputs($ar, '  public $driv, $host, $port, $user, $pass,  $dbname, $dbh;');
        fputs($ar, "\n");
        fputs($ar, "\n");
        fputs($ar, 'function __construct(){');
        fputs($ar, "\n");
        fputs($ar, ' $this->driv; $this->host; $this->port; $this->user; $this->pass;  $this->dbname; $this->dbh;');
        fputs($ar, "\n");
        fputs($ar, '}');
        fputs($ar, "\n");
        fputs($ar, "\n");
        foreach ($config as $key => $value):
          fputs($ar, "\n");
        fputs($ar, '/** Inicio  del method  de ' . $key . '  */');
        fputs($ar, "\n");
        fputs($ar, 'public function '.$key.'(){');
        fputs($ar, "\n");
        fputs($ar, "  // Driver de Conexion con la de base de datos");
        fputs($ar, "\n");
        self::validateDriverConexion($value['driv']);
        fputs($ar, '  $this->driv = \''.$value['driv'].'\';');
        fputs($ar, "\n");
        fputs($ar, "  // IP o HOST de comunicacion con el servidor de base de datos");
        fputs($ar, "\n");
        fputs($ar, '  $this->host = \''.$value['host'].'\';');
        fputs($ar, "\n");
        fputs($ar, "  // Puerto de comunicacion con el servidor de base de datos");
        fputs($ar, "\n");
        fputs($ar, '  $this->port = \''.$value['port'].'\';');
        fputs($ar, "\n");
        fputs($ar, "  // Nombre base de datos");
        fputs($ar, "\n");
        fputs($ar, '  $this->dbna = \''.$value['dbh'].'\';');
        fputs($ar, "\n");
        fputs($ar, "  // Usuario de acceso a la base de datos");
        fputs($ar, "\n");
        fputs($ar, '  $this->user = \''.$value['user'].'\';');
        fputs($ar, "\n");
        fputs($ar, "  // Clave de ac  ceso a la base de datos");
        fputs($ar, "\n");
        fputs($ar, '  $this->pass = \''.$value['pasw'].'\';');
        fputs($ar, "\n");
        fputs($ar, '  return $this;');
        fputs($ar, "\n");
        fputs($ar, '}');
        fputs($ar, "\n");
        fputs($ar, '/** Fin del caso del method de ' . $key . ' */');
        fputs($ar, "\n");
        endforeach;
        fputs($ar, " }");
        fputs($ar, " \n");
        fputs($ar, "?>");
            // Cierro el archivo y la escritura
        fclose($ar);
      }else {
        throw new Exception('El archivo <b>ConfigDatabase.php</b> se esta construyendo.');
      }
      return true;
    }

    public function validateFileIdentico(){
      $fileCofNol = __DIR__.'/ConfigDatabase.php';
      $fileTmpNol = __DIR__.'/ConfigDatabaseTmp.php';
      $fileCofMd5 = md5(@file_get_contents($fileCofNol));
      $fileTmpMd5 = md5(file_get_contents($fileTmpNol));
      if($fileCofMd5 != $fileTmpMd5){
        copy($fileTmpNol, $fileCofNol);
      }
      return true;
    }

    public function validateDriverConexion($driver){
      $arrayName = PDO::getAvailableDrivers();
      if (!in_array($driver, $arrayName)) {
        throw new Exception('El archivo <b>databases.ini</b> solicitaron el driver <b>'.$driver.'</b> por PDO que no esta soportado por el servidor.');
      }
    }
    //función para cerrar una conexión pdo
    public function close_con()
    {
      $this->conn = null;
      return $this->conn;
    }
  }
  ?>
