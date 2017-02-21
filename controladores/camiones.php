<?php
require 'datos/ConexionBD.php';
class camiones
{
public static $tablename = "trucks";  

    const NOMBRE_TABLA = "trucks";
    const ID_CAMION = "id";
    const MODELO = "modelo";
    const MATRICULA = "matricula";
    const LLAVE = "llave";
    const COLOR = "color";
    const CAPACIDAD = "capacidad";
    const REGISTRO = "created_at";


    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;

    public static function get($peticion)
    {

      if (isset($peticion['id'])){
          $idCamion = $peticion['id'];    
      }
      else {
          $idCamion = null;
      } 

      $obtener  = $peticion['PATH_INFO'];

      if ($obtener == "camiones/obtenerCamiones" ){       


         if ($idCamion == null ) {                  
           return self::obtenerCamiones();
       }

         else{
           return self::obtenerCamionesId($idCamion);
       } 

   }  
   return "URLrest incorrecta";
}

    private function obtenerCamiones()
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename;
        
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

    private function obtenerCamionesId($idCamion)
    {
        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where id=".$idCamion;
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
   

