<?php 
class llave
{
public static $table_trucks = "trucks";
public static $table_orden  = "orden";
public static $table_detail = "orden_detail";
public static $table_vales  = "vales";

    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;

    public static function get($peticion)
    {
      $llave = $peticion['llave'];    
      $obtener = $peticion['PATH_INFO'];

      if ($obtener == "llave/verificarCamion" ){       
        return self::obtenerCamionesLlave($llave);
       } 
      	  
	  else if ($obtener == "llave/verificarPedidosImprimir") {
        return self::obtenerPedidosImprimirLlave($llave);
      }
	  
	  else if ($obtener == "llave/verificarPedidosNoActivos") {
        return self::obtenerPedidosNoActivosLlave($llave);
      }
	  
	  else if ($obtener == "llave/verificarPedidosActivos") {
        return self::obtenerPedidosActivosLlave($llave);
      }
	  
	  else if ($obtener == "llave/verificarPedidosCompletados") {
        return self::obtenerPedidosCompletadosLlave($llave);
      }
	  
	  else if ($obtener == "llave/verificarPedidosCancelados") {
        return self::obtenerPedidosCanceladosLlave($llave);
      }
	  
      else if ($obtener == "llave/verificarValesImprimir") {
        return self::obtenerValesImprimir($llave);
      }
	  
	  else if ($obtener == "llave/verificarValesNoActivos") {
        return self::obtenerValesNoActivos($llave);
      }
	  
      else if ($obtener == "llave/verificarValesActivos") {
        return self::obtenerValesActivos($llave);
      }	
	  
	  else if ($obtener == "llave/verificarValesCobrados") {
        return self::obtenerValesCobrados($llave);
      }
	  
      else {
        return "URLrest incorrecta";
      }
   }  
    
 private function obtenerCamionesLlave($llave)
    {
        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_trucks." where llave='$llave'";        
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

private function obtenerPedidosImprimirLlave($llave)
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_orden." where llaveP='$llave' AND status_pedido=1";        
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

private function obtenerPedidosNoActivosLlave($llave)
    {
        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_orden." where llaveP='$llave' AND status_pedido=2";        
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

private function obtenerPedidosActivosLlave($llave)
    {
        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_orden." where llaveP='$llave' AND status_pedido=3";        
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

private function obtenerPedidosCompletadosLlave($llave)
    {
        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_orden." where llaveP='$llave' AND status_pedido=4";        
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
		
private function obtenerPedidosCanceladosLlave($llave)
    {
        
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_orden." where llaveP='$llave' AND status_pedido=5";        
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
		
private function obtenerValesImprimir($llave) 
    {        
	    list($cliente, $pedido, $material,$vale) = split('[/.-]', $llave);
		$referenciavale= $cliente."-".$pedido."-".$material;
		$pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_detail." where status=1 and foliovale='$referenciavale'";        
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
		
  private function obtenerValesNoActivos($llave) 
    {   
        list($cliente, $pedido, $material,$vale) = split('[/.-]', $llave);
		$referenciavale= $cliente."-".$pedido."-".$material;	
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_detail." where status=2 and foliovale='$referenciavale'";        
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

  private function obtenerValesActivos($llave) 
    {    
        list($cliente, $pedido, $material,$vale) = split('[/.-]', $llave);
		$foliovale= $cliente."-".$pedido."-".$material;    
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
		
		$sql = "select * from ".self::$table_vales." where referenciavale='$llave'";        
        $sentencia = $pdo->prepare($sql); 
        $sentencia->execute();
		$result = $sentencia->fetchAll();
		$status =  $result[0][5];
		
        if ($status == 3) {
		    $sql = "select * from ".self::$table_detail." where status=3 and foliovale='$foliovale'";        
            $sentencia = $pdo->prepare($sql); 
            $sentencia->execute();		
     		
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_EXITO,
                        "datos" => $sentencia->fetchAll(PDO::FETCH_ASSOC)
                    ];          	
		}
		
	    else{
	
                return
                    [
                        "estado" => self::ESTADO_EXITO,
                        "datos" => $sentencia->fetchAll(PDO::FETCH_ASSOC)
                    ];
                 
            }	
	}	  
    	 
 
   private function obtenerValesCobrados($llave) 
    {
        $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $sql = "select * from ".self::$table_vales." where referenciavale='$llave' and statusvale=4";        
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