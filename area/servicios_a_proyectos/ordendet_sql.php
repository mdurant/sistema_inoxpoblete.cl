<?php

header('Content-Type: text/html; charset=UTF-8');
require_once("../../conexion/conexion.php");




try
{
    
	if($_GET["action"] == "list")
	{
		$jtSorting=$_GET["jtSorting"];
		$jtStartIndex=$_GET["jtStartIndex"];
		$jtPageSize=$_GET["jtPageSize"];
		$get_val=$_GET["id_eservicios"];
		$bsq=$_POST["Item"];
        

		$from="FROM dservicios a";
		$where="WHERE a.id_eservicios = '$get_val'";
		$limit="LIMIT $jtStartIndex,$jtPageSize";
		
		$sql2=<<<QUERY
		SELECT COUNT(*) AS RecordCount $from $where;
QUERY;

		$result = mysql_query($sql2,conectar::con());
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];
		
		
		$sql=<<<QUERY
			
				SELECT a.id_dservicios, 
				a.id_eservicios, 
				a.posicion, 
				a.codigo, 
				a.descripcion, 
				a.cantidad, 
				a.tipo_impuesto, 
				replace(format(a.valor_neto, 0),',','.') as valor_neto,
				replace(format(a.valor_iva, 0),',','.') as valor_iva,
				replace(format(a.valor_subtotal,0),',','.') as valor_subtotal
                $from $where $limit;		
QUERY;

        $msgerror="";
		try
		{  $result = mysql_query($sql,conectar::con());	} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "Detalle Servicios a Proyectos :: listar :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "Detalle Servicios a Proyectos :: listar :: SQLERROR -> $msgerror -> $sql";};
		
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{

				$rows[] = $row;
		  // $rows[] = array_map('utf8_encode', $row);
			
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = $vRESP;
		$jTableResult['Message']= $vMENSAJE;
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		
		print json_encode($jTableResult);
		
	}
   
	
	conectar::desconectar();
}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}
?>
