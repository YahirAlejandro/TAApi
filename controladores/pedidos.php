<?php
class pedidos
{
public static $tablename = "orden";  

    const NOMBRE_TABLA = "orden";
    const ID_ORDEN = "id_orden";
    const TOTAL = "total";
    const DISCOUNT = "discount";
    const LLAVE = "llaveP";
    const ULTIMA_MOD = "ultima_mod";
    const STATUS_PEDIDO = "status_pedido";
    const REGISTRO = "created_at";

    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;

    public static function get($peticion)
    {

      if (isset($peticion['estatus'])){
          $estatus = $peticion['estatus'];    
      }
	  
	  else if (isset($peticion['llave'])){
		  $estatus = 6;
		  $llave   = $peticion['llave'];
	  }
	  
      else {
          $estatus = null;
      }    

      $obtener  = $peticion['PATH_INFO'];
      
 if ($obtener == "pedidos/obtenerPedidos" ){    

     if ($estatus==1) {
         return self::obtenerPedidosPendientes($estatus);
     } 
     else if ($estatus==2) {
         return self::obtenerPedidosNoActivos($estatus);  
     }
     else if ($estatus==3) {
         return self::obtenerPedidosActivos($estatus); 
     }
     else if ($estatus==4) {
         return self::obtenerPedidosCompletados($estatus);  
     }
	 else if ($estatus==5) {
         return self::obtenerPedidosCancelados($estatus);  
     }
	 else if ($estatus==6){
		 return self::obtenerPedidosConLlave($llave);
	 }
 }   

 return "URLrest incorrecta";        
}

    private function obtenerPedidosPendientes($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status_pedido=".$estatus." order by id_orden desc";
       
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
		
	private function obtenerPedidosNoActivos($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status_pedido=".$estatus." order by id_orden desc";
        
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
    
	private function obtenerPedidosActivos($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status_pedido=".$estatus." order by id_orden desc";
        
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

    private function obtenerPedidosCompletados($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status_pedido=".$estatus." order by id_orden desc";
        
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

    private function obtenerPedidosCancelados($estatus)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where status_pedido=".$estatus." order by id_orden desc";
        
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
		
	private function obtenerPedidosConLlave($llave)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$tablename." where llaveP='$llave'";
        $sentencia = $pdo->prepare($sql); 
        $sentencia->execute();
		$idOrder = $sentencia->fetchAll(PDO::FETCH_ASSOC)[0]['id_orden'];

        if ($sentencia->execute()) {
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_EXITO,
                        "datos" => $sentencia->fetchAll(PDO::FETCH_ASSOC),
						"order" => $idOrder
                    ];
            } else{
                throw new ExcepcionApi(self::ESTADO_ERROR, "Se ha producido un error");
            }

        }

     }
   

