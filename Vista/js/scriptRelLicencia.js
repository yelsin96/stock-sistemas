function ajaxModalE() {
	var id = $("#input-idEquipo").val();
	var nombre = $("#input-nombreEquipo").val();
	var parametros = {
		"id": id,
		"nombre": nombre,
	};
	$.ajax({
		data: parametros,
		url: 'licenciasRelacionAjaxModalEquipo.php',
		type: 'post',
		beforeSend: function () {
			$("#tabla-ajaxEquipo").html("Procesando, espere por favor...");
		},
		success: function (response) {
			$("#tabla-ajaxEquipo").html(response);
		}
	});
}

function seleccionarArt() {
	$(document).on('click', 'button[name="relacionEquipo"]', function (event) {
		var ident = this.id;
		$("#input-placa").val(ident);
		$("#cerrar").trigger("click");
		if($("#eliminar").val()){
			ajaxLicenciasEquipo() // funciona en vista eliminar
		}
	});
}

function ajaxModalL() {
	var id = $("#input-idLicencia").val();
	var nombre = $("#input-nombreLicencia").val();
	var parametros = {
		"id": id,
		"nombre": nombre,
	};
	$.ajax({
		data: parametros,
		url: 'licenciasRelacionAjaxModalLicen.php',
		type: 'post',
		beforeSend: function () {
			$("#tabla-ajaxLicencia").html("Procesando, espere por favor...");
		},
		success: function (response) {
			$("#tabla-ajaxLicencia").html(response);
		}
	});
}

function seleccionarLicencia() {
    console.log("first")
	$(document).on('click', 'button[name="relacionLicencia"]', function (event) {
		var ident = this.id;
		$("#input-licencia").val(ident);
		$("#cerrarL").trigger("click");
	});
}

////////////////////////////////eliminar
function ajaxLicenciasEquipo() {
	//$(document).on('click', 'button[name="relacionEquipo"]', function (event) {
	$(document).ready(function(){
		// var placa = this.id;
		var placa = $("#input-placa").val();
		var parametros = {
			"placa": placa,
		};
		$.ajax({
			data: parametros,
			url: 'licenciasRelacionEliminarAjaxLicencia.php',
			type: 'post',
			beforeSend: function () {
				$("#selectLicenciaAjax").html("Procesando, espere por favor...");
			},
			success: function (response) {
				$("#selectLicenciaAjax").html(response);
			}
		});
	});
}