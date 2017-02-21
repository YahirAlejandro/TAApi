<?php
class accesos
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
		
		$fecha    = $registro->fecha;
        $idCamion = $registro->camion;

        $foto     = $registro->foto;/*esta es mi imagen encode b64*/
		$foto     = str_replace("data:image/jpeg;base64","",$foto);						
		$decoded  = base64_decode($foto);/*esta es mi imagen normalizada*/			
        
		if ($registro->proceso == "E"){
		$date = new DateTime($fecha);
        $result = $date->format('Y-m-d H-i-s');		
		file_put_contents('C:/xampp/htdocs/SIAM/storage/accessTrucks/'.$result.'.jpg', $decoded);	
		return self::access($fecha,$idCamion,$foto);
		}
          		
		else if ($registro->proceso == "S"){
		$date = new DateTime($fecha);
        $result = $date->format('Y-m-d H-i-s');		
		file_put_contents('C:/xampp/htdocs/SIAM/storage/exitTrucks/'.$result.'.jpg', $decoded);	
		return self::access_exit($fecha,$idCamion, $foto);	
		}
		
		return "URLrest incorrecta";

    }	
  
 private function access($idCamion, $fecha, $foto)
    {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $comando = "INSERT INTO access_bank (access_at,camion) VALUES('$idCamion','$fecha')";
				// echo $comando;				
				$sentencia = $pdo->prepare($comando); 
				$sentencia->execute();                
				return [
		              "mensaje" => "Exito query acceso"   
                ];
    }  		

 private function access_exit($idCamion, $fecha, $foto)
    {        
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                $sql = "INSERT INTO exit_bank (exit_date, camion) VALUES ('$idCamion', '$fecha')";
	            $sentencia = $pdo->prepare($sql); 
                $sentencia->execute();
       	        
				return [
	                  "mensaje" => "Exito query salida"           
                ];
    }
}
?>