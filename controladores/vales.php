<?php
class vales
{
public static $tablename = "orden_detail";  

    const NOMBRE_TABLA = "orden_detail";
    const ORDEN        = "orden";
    const PRODUCT      = "product";
    const FOLIOVALE    = "foliovale";
    const estatus      = "estatus";

    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;

    public static function get($peticion)
    {

       if (isset($peticion['estatus'])){
           $estatus  = $peticion['estatus'];    
       }
       else {
           $estatus = null;
       } 

       $obtener  = $peticion['PATH_INFO'];

     if ($obtener == "vales/obtenerVales" ){
       if ($estatus==1) {
           return self::valesPorImprimir($estatus);
       } 
       else if ($estatus==2) {
           return self::impresosNoActivos($estatus); 
       }
       else if ($estatus==3) {
           return self::activos($estatus);  
       }
	   else if ($estatus==4){
		   return self::cobrados($estatus);   
	   }   	   
     }   
      return "URLrest incorrecta";
    }

   
    private function valesPorImprimir($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status=".$estatus." order by orden desc";
       
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

    private function impresosNoActivos($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status=".$estatus." order by orden desc";
       
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
		
    private function activos($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status=".$estatus." order by orden desc";
       
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

    private function cobrados($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status=".$estatus." order by orden desc";
       
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
   

