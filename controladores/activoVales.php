<?php 
class activoVale
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
		
		$folio    = $registro->folio;
		$fecha    = $registro->fecha;        
		$status   = $registro->status;
		$nombre   = $registro->nombre;
		$telefono = $registro->telefono;
		$email    = $registro->email;
				
		$foto     = $registro->foto;/*esta es mi imagen encode b64*/
		$foto     = str_replace("data:image/jpeg;base64","",$foto);						
		$decoded  = base64_decode($foto);/*esta es mi imagen normalizada*/	


		file_put_contents('C:/xampp/htdocs/SIAM/storage/activaciones/'.$folio.'.jpg', $decoded);
		
		$pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $queryCobrados = "SELECT * FROM orden_detail where foliovale='$folio'";
		$sentencia = $pdo->prepare($queryCobrados); 
	    $sentencia->execute();		
        $result = $sentencia->fetchAll();
		
		$statusP = '2';
		$orden   = $result[0][0];
      		
		if ($registro->proceso == "Activar"){
	     
	    self::updatePedidoConValesActivos($statusP,$fecha,$orden); 

        self::activarStatusVale($fecha,$status,$nombre,$folio);
		
	    self::activacionInfo($folio,$fecha,$status,$nombre,$telefono,$email,$foto);
		
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
  
 private function activarStatusVale ($fecha,$status,$nombre,$folio)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "UPDATE orden_detail SET status='$status',fechaentrega='$fecha',recibidopor='$nombre',image='$folio.jpg'  where foliovale='$folio'";
				$sentencia = $pdo->prepare($comando); 
				$sentencia->execute();
                
				return [
		              "mensaje" => "Cambio de status de vale activado"   
                ];
    }  
 
  private function activacionInfo ($folio,$fecha,$status,$nombre,$telefono,$email,$foto)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "INSERT INTO vales (foliovale,ultimamod,statusvale,nombre,telefono,email) values ('$folio','$fecha','$status','$nombre','$telefono','$email')";
			    $sentencia = $pdo->prepare($comando); 
				$sentencia->execute();
                
				return [
		              "mensaje" => "Info de activacion guardada"   
                ];
    }  
	

}
?>