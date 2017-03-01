
        <?php
        /**
  * @propiedad: catatumbo
  * @Autor: Gregorio Bolivar
  * @email: elalconxvii@gmail.com
  * @Fecha de Creacion: 01/03/2017
  * @Auditado por: Gregorio J Bolívar B
  * @Descripción: Generado por el generador de codigo de router de webStores
  * @package: ConfigDatabase.php
  * @version: 1.0
  * @webAutor: http://www.gregoriobolivar.com.ve/
  * @BlogAutor: http://gbbolivar.wordpress.com/
  */ 

class ConfigDatabase
{
  public $driv, $host, $port, $user, $pass,  $dbname, $dbh;

function __construct(){
 $this->driv; $this->host; $this->port; $this->user; $this->pass;  $this->dbname; $this->dbh;
}


/** Inicio  del method  de catatumbo  */
public function catatumbo(){
  // Driver de Conexion con la de base de datos
  $this->driv = 'mysql';
  // IP o HOST de comunicacion con el servidor de base de datos
  $this->host = '127.0.0.1';
  // Puerto de comunicacion con el servidor de base de datos
  $this->port = '3606';
  // Nombre base de datos
  $this->dbna = 'freelance';
  // Usuario de acceso a la base de datos
  $this->user = 'root';
  // Clave de ac  ceso a la base de datos
  $this->pass = '123456';
  return $this;
}
/** Fin del caso del method de catatumbo */
 } 
?>