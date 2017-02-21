<?php 
class statusVale
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
		
		$proceso        = $registro->proceso;
		$fecha          = $registro->fecha;
        $referenciavale = $registro->folio;
		$status         = $registro->status;
		
	    list($cliente, $pedido, $material,$vale) = split('[/.-]', $referenciavale);
		$folio= $cliente."-".$pedido."-".$material;
				
		$pdo = ConexionBD::obtenerInstancia()->obtenerBD();
        $queryCobrados = "SELECT * FROM orden_detail where foliovale='$folio'";
		$sentencia = $pdo->prepare($queryCobrados); 
	    $sentencia->execute();		
        $result = $sentencia->fetchAll();
        
		$valesCobrados =  $result[0][10];
		$totalDeVales  =  $result[0][7]; 
		
		$nuevoTotalVales = $valesCobrados + 1;		
		
		if ($totalDeVales == $nuevoTotalVales){
			
			if ($proceso == "Cobrar"){
		        self::chargeComplete($fecha,$folio,$status,$nuevoTotalVales);
				self::vales($fecha,$referenciavale,$status);				
				return("Todo Bien");			
 		    }
          		
		    else {
		    return "URLrest incorrecta";	
		    }			
		}
		
		else if ($valesCobrados < $totalDeVales){
			
			if ($proceso == "Cobrar"){
		    self::charge($fecha,$folio,$nuevoTotalVales);
		    self::vales($fecha,$referenciavale,$status);				
			return("Todo Bien");		
			}
          		
		    else {
		    return "URLrest incorrecta";	
		    }
		}		
		
        else {
			echo "Todos tus vales de este pedido han sido cobrados";
		}		
						
    }	
  
 private function charge ($fecha,$folio,$nuevoTotalVales)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "UPDATE orden_detail SET ultimamod='$fecha',valescobrados='$nuevoTotalVales' where foliovale='$folio'";
				$sentencia = $pdo->prepare($comando); 
                $sentencia->execute();
                
				return [
		              "mensaje" => "Exito query"   
                ];
    }  	
	
 private function chargecomplete ($fecha,$folio,$status,$nuevoTotalVales)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "UPDATE orden_detail SET status='$status',ultimamod='$fecha',valescobrados='$nuevoTotalVales' where foliovale='$folio'";
				$sentencia = $pdo->prepare($comando); 
				$sentencia->execute();
                
				return [
		              "mensaje" => "Exito query"   
                ];
    }
	
 private function vales ($fecha,$referenciavale,$status)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "UPDATE vales SET statusvale='$status',ultimamod='$fecha' where referenciavale='$referenciavale'";
				$sentencia = $pdo->prepare($comando); 
				$sentencia->execute();
											                
				return [
		              "mensaje" => "Exito query"   
                ];
    }  	

}

?>