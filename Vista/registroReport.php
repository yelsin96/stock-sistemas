<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reporte Registro</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel='stylesheet prefetch' href='css/bootstrap.min.css'>

<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src='js/jquery.min.js'></script>
<script type="text/javascript" src='js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/scriptMovR.js"></script>
</head>
<body>
    <div class="container">
    	<?php
    		session_start();

			if($_SESSION['cargoLogin'] != 'Coordinador Soporte y Mantenimiento' && $_SESSION['rolLogin'] != 'SuperAdministrador' && $_SESSION['procesoLogin'] != 'TIC'){
                session_destroy();
                header('Location: ../../errores/403/index.html');
                exit;
            }
	 
			if(!isset($_SESSION['userLogin'])){
			    header('Location: login.php');
			    exit;
			} else {

			include "Menu.php";
			include '../Controlador/controladorRegistro.php';
		            $registro = new registro;
		?>	
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-4">
						<h2>Detalle de los<b> Movimientos</b></h2>
					</div>
					
                </div>
            </div>
 
            <div class="row text-center" id="loader" style="position: absolute;top: 140px;left: 50%">
				
			</div>
 
 
			<div class="table-filter">
				<div class="row">
                    
                    <div class="col-sm-9">
						<button type="button" class="btn btn-primary"><i class="fa fa-search" onclick="load('1');"></i></button>
						<div class="filter-group">
							<label>Placa</label>
							<input type="text" class="form-control" id="name">
						</div>
						<div class="filter-group">
							<label>Ubicación</label>
							<select class="form-control" id="location" onchange="load(1);">
			                    <option value="">Todos</option>
			                    <?php
			                        $resultadoUbicacion = $registro->consultarUbicacion();
			                        while ($valores = mysqli_fetch_array($resultadoUbicacion)) {
			                            echo '<option value="'.$valores["id"].'">'.$valores["descripcion"].'</option>';
			                        }
			                    ?>  
			                </select>
						</div>
						<div class="filter-group">
							<label>Articulo</label>
							<select class="form-control" id="status" onchange="load(1);">
								<option value="">Todos</option>
								<?php
			                        $resultadoArticulo = $registro->consultarArticuloFiltro();
			                        while ($valores1 = mysqli_fetch_array($resultadoArticulo)) {
			                            echo '<option value="'.$valores1["descripcion"].'">'.$valores1["descripcion"].'</option>';
			                        }
			                    ?>  
							</select>
						</div>
						<span class="filter-icon"><i class="fa fa-filter"></i></span>
                    </div>
 
                    <div class="col-sm-3 text-right">
						<div class="show-entries">
							<span>Mostrar</span>
							<select class="form-control" id="per_page" onchange="load(1);">
								<option>5</option>
								<option>10</option>
								<option selected="">15</option>
								<option>20</option>
							</select>
							
						</div>
					</div>
 
 
                </div>
			</div>
		<div class="datos_ajax">
 
		</div>	
            
			
        </div>
    </div>     
	<?php } ?>
</body>
</html>