<?php
session_start();
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	
	include('../Controlador/controladorReportArt.php');
	$database=new orders();
	
	//Recibir variables enviadas
	$query=strip_tags($_REQUEST['query']);
	$location=strip_tags($_REQUEST['location']);
	$status=strip_tags($_REQUEST['status']);
	$per_page=intval($_REQUEST['per_page']);
	$tables="articulo as art";
	$campos="art.placa,art.descripcion,tip.descripcion tipo,ub.descripcion ubicacion,ub.id Sucursal,art.observacion,art.id_datos";
	//$campos="*";
	//Variables de paginación
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$adjacents  = 4; //espacio entre páginas después del número de adyacentes
	$offset = ($page - 1) * $per_page;
	
	$search=array("query"=>$query,"location"=>$location,"status"=>$status,"per_page"=>$per_page,"offset"=>$offset);
	//consulta principal para recuperar los datos
	$datos=$database->getData($tables,$campos,$search);
	$countAll=$database->getCounter();
	$row = $countAll;
	
	if ($row>0){
		$numrows = $countAll;;
	} else {
		$numrows=0;
	}	
	$total_pages = ceil($numrows/$per_page);
	
	
	//Recorrer los datos recuperados
 
	if ($numrows>0){
		?>
	 <table class="table table-striped table-hover ">	
		<thead>
			<tr>
				<!--<th>#</th>-->
				<th>Placa</th>
				<th>Descripcion</th>
				<th>Tipo</th>
				<th>Ubicacion</th>						
				<th>Observacion</th>
				<th><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></th>						
			</tr>
		</thead>
		<tbody>
		<?php
		$finales=0;
		/*$hola=mysqli_fetch_array($datos);
		print_r($hola);*/
		foreach ($datos as $key=>$row){
				/*$status_order=$row['status'];
				if ($status_order=='Entregado'){
					$class_css="text-success";
				} elseif ($status_order=='Cancelado'){
					$class_css="text-danger";
				} elseif ($status_order=='Pendiente'){
					$class_css="text-warning";
				} elseif ($status_order=='Enviada'){
					$class_css="text-info";
				} else {
					$class_css="";
				}*/
			?>
		<tr>
			<td style="width: 10%;"><span class="glyphicon glyphicon-file" aria-hidden="true"></span><?=$row['placa'];?></td>
			<td><?=$row['descripcion'];?></td>
			<td><?=$row['tipo'];?></td>                  
			<td><?=$row['ubicacion'];?>-<?=$row['Sucursal'];?></td>
			<td><?=$row['observacion'];?></td>
			<td>
			<?php
			if($row['id_datos'] <> NULL ){
				echo "<a href='mirarCaracteristica.php?placa=" . $row["placa"] . "' ><svg style='color:blue'  xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-search' viewBox='0 0 16 16'>
				 <path d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/>
				</svg>  </a>";
			} 
			?>
			</td>
			
			<!--<td><a href="#" class="view" title="View Details" data-toggle="tooltip"><i class="material-icons">&#xE5C8;</i></a></td>Icono para redirigir-->
		</tr>
			<?php
			$finales++;
		}	
	?>
		</tbody>
		</table>
		<div class="clearfix">
			<?php 
				$inicios=$offset+1;
				$finales+=$inicios -1;
			echo '<div class="hint-text">Mostrando ' . $inicios . ' al ' . $finales . ' de ' . $numrows . ' registros</div>';
				
				include '../Controlador/pagination.php'; //include pagination class
				$pagination=new Pagination($page, $total_pages, $adjacents);
				echo $pagination->paginate();
 
			?>
		</div>
	<?php
	}
}
?>