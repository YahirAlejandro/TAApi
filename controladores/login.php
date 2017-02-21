<?php 
class login
{
public static $table_user = "user";

    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;

    public static function get($peticion)
    {
	  $user = $peticion['usuario'];    
      $password = $peticion['password'];
	  $pass = sha1(md5($password));
	  $obtener = $peticion['PATH_INFO'];

      if ($obtener == "login/verificarUser" ){       
        return self::obtenerUser($user, $pass);
       } 
      
      else {
        return "URLrest incorrecta";
      }
   }  
   
 private function obtenerUser($user,$pass)
    {
        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_user." where username='$user' and password='$pass' ";  

        $sentencia = $pdo->prepare($sql); 
        $sentencia->execute();

        if ($sentencia->execute()) {
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_EXITO,
                        "datos" => $sentencia->fetchAll(PDO::FETCH_ASSOC)
                    ];
            } else{
                throw new ExcepcionApi(self::ESTADO_ERROR, "Se ha producido un error");
            }

        }

}		
?>