<?php 
	class articulo{
		public $conn;
		public $prueba="conexion";
		public function __construct(){
			require_once '../Modelo/conexion.php';
			$conectar=new conectar($_SESSION['sedeStock']);
			$this->conn=$conectar->conexion();
		}

		public function consultarTipo(){
			$consultaTipo = "SELECT * FROM tipo_articulo";
            $resultadoTipo = mysqli_query( $this->conn, $consultaTipo );
			return $resultadoTipo;
		}

		public function consultarUbicacion(){
			$consultaUbicacion = "SELECT * FROM `ubicacion` ORDER BY `ubicacion`.`descripcion` ASC";
            $resultadoUbicacion = mysqli_query( $this->conn, $consultaUbicacion );
			return $resultadoUbicacion;
		}

		public function consultarArticulo($placaM){
			$consultaArticulo = "SELECT * FROM `articulo` WHERE placa = '".$placaM."'";
            $resultadoArticulo = mysqli_query( $this->conn, $consultaArticulo );
			return $resultadoArticulo;
		}

		public function insertarArticulo($placa,$descripcion,$tipo,$ubicacion,$observacion,$login){
			if ($_SESSION['sedeStock'] == "Servired") {
				$ubicacion = "1009"; 
			}else{
				$ubicacion = "9008"; 
			}
			
			$sql = "INSERT INTO `articulo`( `placa`, `descripcion`, `tipo_id`, `ubicacion_id`, `observacion`)";
            $sql.= "VALUES ('".$placa."', '".$descripcion."','".$tipo."',".$ubicacion.",'".$observacion."')";
          	$resultado = mysqli_query( $this->conn, $sql );
          	if ($resultado==TRUE) {
          		$sqlHistorial = "INSERT INTO `historial`(`id`, `usuario`, `operacion`,`tabla`, `id_relacionado`, `fecha`)";
	            $sqlHistorial.= "VALUES (NULL,'".$login."', 'ingreso placa: ".$placa." descripcion: ".$descripcion." tipo_id: ".$tipo." ubicacion: ".$ubicacion." observacion: ".$observacion."','Articulo','".$placa."',NOW())";

            	$resultadoHistorial = mysqli_query( $this->conn, $sqlHistorial );

		        echo "<div class='alert alert-success alert-dismissible'>";
				echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "  <strong>Excelente!</strong> Se ingreso Articulo ".$placa." correctamente.";
				echo "</div>";
				echo "<div class='alert alert-info alert-dismissible'>";
				echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "	<strong>Desea ingresar carecteristicas del equipo ".$placa." !  </strong>";
				echo "	<a href='ingresarCaracteristicas.php?activo=".$placa."'><input type='button' class='btn btn-primary' value='insertar'></a> ";
				echo "</div>";

          	}else{
		        echo "<div class='alert alert-danger alert-dismissible'>";
				
				echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "  <strong>Error!</strong> ".mysqli_error($this->conn);
				echo "</div>";
          	}
		}

		public function modificarArticulo($placa,$descripcion,$tipo,$ubicacion,$observacion,$datos,$login){
			if ($datos=="") {
				$sql = "UPDATE `articulo` SET `descripcion`='".$descripcion."',`tipo_id`='".$tipo."',`ubicacion_id`='".$ubicacion."',`observacion`= '".$observacion."',`id_datos`= NULL WHERE `articulo`.`placa` = '".$placa."'";
			}else{
				$sql = "UPDATE `articulo` SET `descripcion`='".$descripcion."',`tipo_id`='".$tipo."',`ubicacion_id`='".$ubicacion."',`observacion`= '".$observacion."',`id_datos`= '".$datos."' WHERE `articulo`.`placa` = '".$placa."'";
			}
          	$resultado = mysqli_query( $this->conn, $sql );
          	if ($resultado==TRUE) {
          		$sqlHistorial = "INSERT INTO `historial`(`id`, `usuario`, `operacion`,`tabla`, `id_relacionado`, `fecha`)";
	            $sqlHistorial.= "VALUES (NULL,'".$login."', 'modifico placa: ".$placa." descripcion: ".$descripcion." tipo_id: ".$tipo." ubicacion: ".$ubicacion." observacion: ".$observacion."','Articulo','".$placa."',NOW())";

            	$resultadoHistorial = mysqli_query( $this->conn, $sqlHistorial );

		        echo "<div class='alert alert-success alert-dismissible'>";
				echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "  <strong>Excelente!</strong> Se Modifico Articulo correctamente.";
				echo "</div>";

          	}else{
		        echo "<div class='alert alert-danger alert-dismissible'>";
				echo "  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
				echo "  <strong>Error!</strong> ".mysqli_error($this->conn).$sql;
				echo "</div>";
          	}
		}
	}
 ?>