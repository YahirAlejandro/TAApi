<?php
class detalles
{
public static $tablename = "orden_detail";  

    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;

	public static function get($peticion)
    {		
	$obtener  = $peticion['PATH_INFO'];	
	    
    if ($obtener == "detalles/obtenerDetalles" ){ 	
	
      if (isset($peticion['idorder'])){
          $idOrder = $peticion['idorder'];
          return self::obtenerDetallesPedidos($idOrder);		  
		  	  
      }
	  else if (isset ($peticion['referencia'])){
		  $referencia = $peticion['referencia'];
		  return self::obtenerDetallesVales($referencia);
	  }
	  
	  else if (isset ($peticion['idcamion'])){
		  $idcamion = $peticion['idcamion'];
		  return self::obtenerDetallesCamiones($idcamion);
	  }  
      
      else{
		  return "URLrest incorrecta";
	  }	   
	}	    
	
    }

    private function obtenerDetallesPedidos($idOrder)
    {        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where orden=".$idOrder;        
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
	
	private function obtenerDetallesVales($referencia)
    {        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where foliovale='$referencia'"; 
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
	
	private function obtenerDetallesCamiones($idcamion)
    {        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from trucks where id=$idcamion";
        $sentencia = $pdo->prepare($sql); 
        $sentencia->execute();
       
		$Image = $sentencia->fetchAll(PDO::FETCH_ASSOC)[0]['image'];
		$ruta  = 'C:xampp/htdocs/SIAM/storage/trucks/'.$Image;
		$archivo   = file_get_contents($ruta);
		$imgEncode = base64_encode($archivo);	
	    
		if ($sentencia->execute()) {
		        http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_EXITO,
                        "datos"  => $sentencia->fetchAll(PDO::FETCH_ASSOC),
						"imagen" => $imgEncode
                    ];
            } else{
                throw new ExcepcionApi(self::ESTADO_ERROR, "Se ha producido un error");
            }
    }

}