<?php 
	class simcard{
		public $conn;

		public function __construct(){
			require_once '../Modelo/conexion.php';
			$conectar=new conectar($_SESSION['sedeStock']);
			$this->conn=$conectar->conexion();
		}


		public function insertarSimcard($linea,$serie,$usuario,$clave,$apn,$plan,$operador,$ubicacion,$observacion,$login){
			if ($_SESSION['sedeStock'] == "Servired") {
				$ubicacion = "1009"; 
			}else{
				$ubicacion = "9008"; 
			}
			$sql = "INSERT INTO `simcard`( `Numero_linea`,`serie`, `usuario`, `clave`, `Apn`, `Plan`, `Operador`, `Ubicacion_id`, `Observacion`)";
            $sql.= "VALUES ('".$linea."', '".$serie."','".$usuario."','".$clave."','".$apn."','".$plan."','".$operador."',".$ubicacion.",'".$observacion."')";
          	$resultado = mysqli_query( $this->conn, $sql );
          	if ($resultado==TRUE) {
          		$sqlHistorial = "INSERT INTO `historial`(`id`, `usuario`, `operacion`,`tabla`, `id_relacionado`, `fecha`)";
	            $sqlHistorial.= "VALUES (NULL,'".$login."', 'ingreso Simcard: ".$linea." serie: ".$serie." usuario: ".$usuario." clave: ".$clave." Apn: ".$apn." plan: ".$plan." ubicacion: ".$ubicacion." observacion: ".$observacion."','Simcard',".$linea.",NOW())";
            	$resultadoHistorial = mysqli_query( $this->conn, $sqlHistorial );

		        echo "<div class='alert alert-success alert-dismissible'>";
				echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "  <strong>Excelente!</strong> Se ingreso Articulo correctamente.";
				echo "</div>";

          	}else{
		        echo "<div class='alert alert-danger alert-dismissible'>";
				echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "  <strong>Error!</strong> ".mysqli_error($this->conn);
				echo "</div>";
          	}
		}

		public function consultarOperador(){
			$consultaTipo = "SELECT * FROM operador";
            $resultadoTipo = mysqli_query( $this->conn, $consultaTipo );
			return $resultadoTipo;
		}

		public function consultarSimcardOperador($operador){
			$consultaSimcard = "SELECT * FROM `simcard` WHERE  operador =".$operador." and Ubicacion_id is null or Ubicacion_id <> '98' ORDER BY Numero_linea ASC";
            $resultadoSimcard = mysqli_query( $this->conn, $consultaSimcard );
			return $resultadoSimcard;
		}

		public function consultarSimcardModal($linea, $serie, $operador){
			$consultaSimcard = "SELECT * FROM `simcard` WHERE  operador =".$operador." and Numero_linea like '%".$linea."%' and Serie like '%".$serie."%' ORDER BY `simcard`.`Numero_linea` ASC";
            $resultadoSimcard = mysqli_query( $this->conn, $consultaSimcard );
			return $resultadoSimcard;
		}

		public function consultarUbicacion(){
			$consultaUbicacion = "SELECT * FROM `ubicacion` ORDER BY `ubicacion`.`descripcion` ASC";
            $resultadoUbicacion = mysqli_query( $this->conn, $consultaUbicacion );
			return $resultadoUbicacion;
		}

		public function consultarUsuario($empresa){
			require_once '../Modelo/conexionLogin.php';
			$conectarL=new conectarUsuarios();
			$connL=$conectarL->conexionUsuarios();

			$consultaUsuario= "SELECT * FROM `Personas` p ";
			$consultaUsuario.= "inner join  empresa e on p.id_empresa = e.id_empresa ";
			$consultaUsuario.= "where id_estado = 1 and nombre_empresa = '$empresa' ORDER BY `nombre_persona` ASC";
			
            $resultadoUsuario = mysqli_query( $connL, $consultaUsuario );
			return $resultadoUsuario;
		}

		public function insertarRegistroSimcard($simcard,$ubicacion,$incidente,$usuario,$login){
			$consultaSimcard = "SELECT * FROM `simcard` WHERE Numero_linea =".$simcard;
            $resultadoSimcard = mysqli_fetch_array(mysqli_query( $this->conn, $consultaSimcard ));
            
            if ($resultadoSimcard['Ubicacion_id'] == $ubicacion) {
            	echo "<div class='alert alert-danger alert-dismissible'>";
				echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "  <strong>Error!</strong> Simcard ya se encuentra en la ubicacion solicitada";
				echo "</div>";
            }else{
				$sql = "INSERT INTO `movimientossimcard`(`id`, `simcard_id`, `ubicacion_id`, `incidente`, `fecha`, `usuario_realiza`)";
	            $sql.= "VALUES (NULL,'".$simcard."', '".$ubicacion."','".$incidente."',NOW(),'".$usuario."')";

	          	$resultado = mysqli_query( $this->conn, $sql );

	          	if ($resultado==TRUE) {
	          		$consultaUltimoId="SELECT id FROM `movimientossimcard` order by id DESC LIMIT 1";
	            	$resultadoUltimoId=mysqli_fetch_array(mysqli_query( $this->conn, $consultaUltimoId ));

	          		$sqlHistorial = "INSERT INTO `historial`(`id`, `usuario`, `operacion`, `tabla`, `id_relacionado`, `fecha`)";
	            	$sqlHistorial.= "VALUES (NULL,'".$login."', 'ingreso simcard: ".$simcard." ubicacion: ".$ubicacion." incidente: ".$incidente." usuario: ".$usuario."','MovimientosSimcard','".$resultadoUltimoId['id']."',NOW())";
	            	$resultadoHistorial = mysqli_query( $this->conn, $sqlHistorial );

			        echo "<div class='alert alert-success alert-dismissible'>";
					echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo "  <strong>Excelente!</strong> Se ingreso Registro Simcard correctamente.";
					echo "</div>";

	          	}else{
			        echo "<div class='alert alert-danger alert-dismissible'>";
					echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
					echo "  <strong>Error!</strong> ".mysqli_error($this->conn);
					echo "</div>";
	          	}
	        }
		}

		public function modificarSimcard($simcard,$ubicacion){
			$sql = "UPDATE `simcard` SET `Ubicacion_id`= '".$ubicacion."' where Numero_linea = ".$simcard;
          	$resultado = mysqli_query( $this->conn, $sql );
		}

		/*--------------------------------------------------------------
* Función encargada de exportar a excel.
* Recibe como parametro un arreglo de datos.
*---------------------------------------------------------------*/

	    function exportProductDatabase() {

	    	$sql ="SELECT sim.Numero_linea,CONCAT('S-',serie) serie,sim.Usuario,sim.Clave,sim.Apn,sim.Plan,ope.descripcion OperadorD, ub.descripcion Ubicacion,sim.Observacion FROM simcard as sim LEFT JOIN ubicacion ub on sim.Ubicacion_id = ub.id inner JOIN operador as ope on sim.operador = ope.id where sim.Numero_linea LIKE '%%' order by sim.operador asc";
	    	$Result = mysqli_query( $this->conn, $sql );

	    	$productResult = array();

			while( $rows = mysqli_fetch_assoc($Result) ) {
				$productResult[] = $rows;
			}

			$filename = "Simcard.xls";

			header("Content-Type: application/vnd.ms-excel");

			header("Content-Disposition: attachment; filename=".$filename);

			$mostrar_columnas = false;

			foreach($productResult as $libro) {

				if(!$mostrar_columnas) {

					echo implode("\t", array_keys($libro)) . "\n";

					$mostrar_columnas = true;

				}

				echo implode("\t", array_values($libro)) . "\n";

			}
	    } 
	}
 ?>