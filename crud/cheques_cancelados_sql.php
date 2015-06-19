<?php

header('Content-Type: text/html; charset=UTF-8');

require_once("../validatesession_json.php");

require_once("../conexion/funciones.php");
$func = new funciones();

$PERMISOS_AFP=array();
$PERMISOS_AFP=$func->ISAUTORIZED($_SESSION['SESS_PERFILID'], 'CHEQUES_CANCELADOS');


require_once("../conexion/conexion.php");

try
{
    
	if($_GET["action"] == "list")
	{

		if (!$PERMISOS_AFP['LISTAR']=='1'){
		
			$jTableResult = array();
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message']= "CHEQUES_CANCELADOS :: LISTAR :: Acceso denegado.";
			$jTableResult['TotalRecordCount'] = 0;
			$jTableResult['Records'] = array();
			
			print json_encode($jTableResult);
			die;
			
		}

            $inicio = date("Y-m-d", strtotime($_POST["inicio"]));
            $fin = date("Y-m-d", strtotime($_POST["fin"]));
            $dat="(A.fecha_pago BETWEEN '".$inicio."' AND '".$fin."')";
      		

      $jtSorting=$_GET["jtSorting"];
			$jtStartIndex=$_GET["jtStartIndex"];
			$jtPageSize=$_GET["jtPageSize"];

	$from="FROM `compromiso_pago_proveedores` as A 
				inner join ecompra as B on
				A.`id_compra` = B.id_ecompra
				inner join banco C on
				C.id_banco = A.id_banco";
	$where="WHERE A.voucher > 0
				or
				($dat)
				GROUP BY A.id_compra $radio";
	$limit="LIMIT $jtStartIndex,$jtPageSize";
	
	$sql2=<<<QUERY
		SELECT COUNT(*) AS RecordCount $from $where;
QUERY;

    $result = mysql_query($sql2,conectar::con());
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];
		
		
        $sql=<<<QUERY
				Select  DISTINCT A.id_compra, A.tipo_compromiso, 
				replace (A.`voucher`, ',','--') as voucher, 
				A.fecha_pago,B.folio_factura,
				concat ('$' ,' ' ,format(A.monto_final, 0)) as monto_final,
				C.nombre_banco
				$from  
        $where
				ORDER BY $jtSorting
				$limit;		
QUERY;


        $msgerror="";
		try
		{  $result = mysql_query($sql,conectar::con());	


		} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "Cheques Cancelados :: listar :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "Cheques Cancelados :: listar :: SQLERROR -> $msgerror -> $sql";};
		
		
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
     else if($_GET["action"] == "create")
	{
		

		if (!$PERMISOS_AFP['CREAR']=='1'){
		
			$jTableResult = array();
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message']= "CHEQUES_CANCELADOS :: CREAR :: Acceso denegado.";
			$jTableResult['TotalRecordCount'] = 0;
			$jTableResult['Records'] = array();
			
			print json_encode($jTableResult);
			die;
			
		}


		//Insert record into database
        $Id_afp=$_POST["Id_afp"];
        $nombre_afp=$_POST["nombre_afp"];
        $cotizacion=$_POST["cotizacion"];
        $codigo=$_POST["codigo"];
        $Estado=$_POST["Estado"];
 
		$sql=<<<QUERY
		INSERT INTO  afp (
                        Id_afp ,
                        nombre_afp ,
                        cotizacion ,
                        codigo,
                        Estado
                        )
                        VALUES (
                        NULL , '$nombre_afp',  '$cotizacion','$codigo','$Estado'
                        );



		
QUERY;

		$msgerror="";
		try
		{  $result = mysql_query($sql,conectar::con());} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "formapago :: Ingresar :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "formapago :: Ingresar :: SQLERROR -> $msgerror -> $sql";};
		
					
		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = $vRESP;
		$jTableResult['Message']= $vMENSAJE;
		$jTableResult['Record'] = $result;
		print json_encode($jTableResult);
	}
	
	//Updating a record (updateAction)
	else if($_GET["action"] == "update")
	{

		if (!$PERMISOS_AFP['MODIFICAR']=='1'){
		
			$jTableResult = array();
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message']= "CHEQUES_CANCELADOS :: MODIFICAR :: Acceso denegado.";
			$jTableResult['TotalRecordCount'] = 0;
			$jTableResult['Records'] = array();
			
			print json_encode($jTableResult);
			die;
			
		}


        $Id_afp=$_POST["Id_afp"];
        $nombre_afp=$_POST["nombre_afp"];
        $cotizacion=$_POST["cotizacion"];
        $codigo=$_POST["codigo"];
        $Estado=$_POST["Estado"];
	
			$sql=<<<QUERY
		
		UPDATE  afp SET  nombre_afp =  '$nombre_afp',
                cotizacion =  '$cotizacion',
                codigo = '$codigo',
                Estado =  '$Estado' WHERE  afp.Id_afp ='$Id_afp';
		
QUERY;
		
		
		
		//die($sql);
		
		$msgerror="";
		try
		{ $result = mysql_query($sql,conectar::con());} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "comunas :: Modificar :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "comunas :: Modificar :: SQLERROR -> $msgerror -> $sql";};
		


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = $vRESP;
		$jTableResult['Message']= $vMENSAJE;
		print json_encode($jTableResult);
		
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{

		if (!$PERMISOS_AFP['ELIMINAR']=='1'){
		
			$jTableResult = array();
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message']= "CHEQUES_CANCELADOS :: ELIMINAR :: Acceso denegado.";
			$jTableResult['TotalRecordCount'] = 0;
			$jTableResult['Records'] = array();
			
			print json_encode($jTableResult);
			die;
			
		}
		
    	$Id_afp=$_POST["Id_afp"];

		$delete=<<<QUERY
		UPDATE  afp SET  Estado =  'inactivo' WHERE  afp.Id_afp ='$Id_afp';
QUERY;

		$msgerror="";
		try
		{ $result = mysql_query($delete,conectar::con());} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "Cotizacion :: Facturada :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "Cotizacion :: Facturada :: SQLERROR -> $msgerror -> $sql";};


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = $vRESP;
		$jTableResult['Message']= $vMENSAJE;
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
