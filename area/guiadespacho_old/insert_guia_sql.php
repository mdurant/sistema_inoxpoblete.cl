<?php
require_once("../../validatesession_json.php");
require_once("guia_sql.php");

$tra=new cargar_guia();

// GLOBALES

$Empresa = $_SESSION['SESS_EMPRESA_ID'];
$usuario = $_SESSION['SESS_USER_ID'];


	//Capturo la fecha de inicio y termino de la guia de despacho
	date_default_timezone_set("Chile/Continental");
	$f1=$_POST["fecha_guia"];//nuevo
	$fecha_guia=date('Y-m-d', strtotime($f1));
	$jefe=$_POST["jefe"];
	$glosa=$_POST["glosa"];
	$proyecto = $_POST["proyecto"];
	$patente=$_POST["patente"];
	$folio=$_POST["folio"];
	$iva_guia =($neto_guia*19)/100;
	$total_gral = $neto_guia+$iva;
	$contador=$tra->ObtieneGuia();
	$clientes=$_POST["clientes"];
	$neto_guia=$_POST["neto_guia"];
	$motivo=$_POST["motivo_guia"];
	$rut_chofer =$_POST["rut_chofer"];
	$nombre_chofer=$_POST["nombre_chofer"];
	$placa=$_POST["placa_patente"];
	$autorizado_por=$_POST["autorizado_por"];
	// Este campo me sirve para actualizar en caso de ser asignado en Factura
	//$folio=0;

	$idet=$tra->insertar_eguia($contador,$folio,'0',$clientes,$proyecto,$neto_guia,$iva_guia,'0.19',$total,$fecha_guia,$empresa,$motivo,$glosa,'pendiente',$rut_chofer,$nombre_chofer,$placa,$autorizado_por);

	//echo json_encode($vare);
	//die($id_ultimo);
	
	//los recorro para hacer el insert
	for($i=0;$i<54;$i++)
	{
		if($_POST["codigo"][$i]=="")
		{
			
		}
		else
		{
		$descripcion=$_POST["descripcion"][$i];
		$posicion=$_POST["posicion"][$i];
		$codigo=$_POST["codigo"][$i];
		$cantidad=$_POST["cantidad"][$i];
		$bodega=$_POST["bodega"][$i];
		$valor=$_POST["valor"][$i];
		$sub_total=$_POST["total_tbl"][$i];

	$tra->insertar_dguia($idet[0],$_POST["posicion"][$i],$_POST["codigo"][$i],$proba,$_POST["cantidad"][$i],$_POST["descuento"][$i],$_POST["bodega"][$i],$neto2,$iva2,$_POST["total_tbl"][$i],$empresa);

			
/*	
	
			$res=$tra->traer_datos($_POST["codigo"][$i]);
			$stocks=$res[0]["UnitsInStock"];
			$ide=$res[0]["IDProduct"];
			$stockNew=$stocks + $_POST["cantidad"][$i];
			$tra->Stock($ide,$stockNew,$precio_venta,$precio_compra);*/
		}
		
	}
	$vare="Se Inserto Correctamente";
	$salida="todo";
	echo json_encode($vare);

	
?>