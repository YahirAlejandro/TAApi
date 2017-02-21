<?php 
class activoPedido
{
    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;
	
public static function post($peticion)
    {	   	
        $body = file_get_contents('php://input');
        $registro = json_decode($body);	
		$statusP  = '3';
		$llaveP   = $registro->llaveP;
		$fecha    = $registro->fecha;        
		$status   = $registro->status;
		$nombre   = $registro->nombre;
		$telefono = $registro->telefono;
		$email    = $registro->email;
				
		$foto     = $registro->foto;/*esta es mi imagen encode b64*/
		$foto     = str_replace("data:image/jpeg;base64","",$foto);						
		$decoded  = base64_decode($foto);/*esta es mi imagen normalizada*/	


		file_put_contents('C:/xampp/htdocs/SIAM/storage/activaciones/'.$llaveP.'.jpg', $decoded);
		
		//obtengo pedido con llave de codeBar
		$pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $queryPedidos = "SELECT * FROM orden where llaveP='$llaveP'";
		$sentencia = $pdo->prepare($queryPedidos); 
	    $sentencia->execute();		
        $result = $sentencia->fetchAll();
		//fin pedido
				
		//obtengo #orden para obtener detalles
		$orden   = $result[0][0];
        //fin 		
		
		//obtengo detalles del pedido
		$queryVales = "SELECT * FROM orden_detail where orden='$orden'";
		$sentencia = $pdo->prepare($queryVales); 
	    $sentencia->execute();		
        $resultV = $sentencia->fetchAll();
		//fin detalles 
		
				
		if ($registro->proceso == "Activar"){
	     
		self::updatePedidoConValesActivos($statusP,$fecha,$orden); 

        self::activarStatusVale($fecha,$status,$nombre,$llaveP,$orden);
		
		foreach ($resultV as $element) {			
    		     $totalVales     = $element[7]; 
		         $referenciaVale = $element[8];			 
			 
			           for ($i = 1; $i <= $totalVales; $i++) {					   					   
			           $referenciaCode = $referenciaVale."-V".$i;
					   self::activacionInfo($llaveP,$referenciaCode,$fecha,$status,$nombre,$telefono,$email,$foto);	
		               } 
		}
					
		return "todo correcto";
		}
         		
		else {
	    return "URLrest incorrecta";	
		}				  	
						
    }	
  
  
 private function updatePedidoConValesActivos ($statusP,$fecha,$orden)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "UPDATE orden SET status_pedido='$statusP',ultima_mod='$fecha' where id_orden='$orden'";
				$sentencia = $pdo->prepare($comando); 
				$sentencia->execute();
                
				return [
		              "mensaje" => "Cambio de status Pedido con Vales Activos"   
                ];
    } 
  
 private function activarStatusVale ($fecha,$status,$nombre,$llaveP,$orden)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "UPDATE orden_detail SET status='$status',fechaentrega='$fecha',recibidopor='$nombre',image='$llaveP.jpg'  where orden='$orden'";
				$sentencia = $pdo->prepare($comando); 
				$sentencia->execute();
                
				return [
		              "mensaje" => "Cambio de status de vale activado"   
                ];
    }  
 
  private function activacionInfo ($llaveP,$referenciaCode,$fecha,$status,$nombre,$telefono,$email,$foto)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "INSERT INTO vales (llave,referenciavale,ultimamod,statusvale,nombre,telefono,email) values ('$llaveP','$referenciaCode','$fecha','$status','$nombre','$telefono','$email')";
			    $sentencia = $pdo->prepare($comando); 
				$sentencia->execute();
                
				return [
		              "mensaje" => "Info de activacion guardada"   
                ];
    }  
	

}
?>