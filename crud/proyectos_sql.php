<?php

header('Content-Type: text/html; charset=UTF-8');

require_once("../validatesession_json.php");




require_once("../conexion/funciones.php");
$func = new funciones();

$PERMISOS_ALMACENES=array();
$PERMISOS_ALMACENES=$func->ISAUTORIZED($_SESSION['SESS_PERFILID'], 'ALMACENES');


require_once("../conexion/conexion.php");




try
{
    
	if($_GET["action"] == "list")
	{

		if (!$PERMISOS_ALMACENES['LISTAR']=='1'){
		
			$jTableResult = array();
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message']= "Proyectos :: LISTAR :: Acceso denegado.";
			$jTableResult['TotalRecordCount'] = 0;
			$jTableResult['Records'] = array();
			
			print json_encode($jTableResult);
			die;
			
		}

		
		if($_POST["proyecto"]=="" or $_POST["proyecto"]=="null")
		{
			$forma="";
		}else
		{
			$forma=$_POST["proyecto"];
		}
        
        if($_POST['inactivo']=="1" or $_POST['inactivo']=="")
        {
            $radio="AND proyecto.Estado='activo'";
        }elseif($_POST['inactivo']=="2")
        {
            $radio="";
        }

        $jtSorting=$_GET["jtSorting"];
		$jtStartIndex=$_GET["jtStartIndex"];
		$jtPageSize=$_GET["jtPageSize"];

		$from="FROM proyectos";
		$where="WHERE Estado = 'activo' and proyectos.nombre_proyecto LIKE '%$forma%' $radio";
		$limit="LIMIT $jtStartIndex,$jtPageSize";
		
		$sql2=<<<QUERY
		SELECT COUNT(*) AS RecordCount $from $where;
QUERY;

        $result = mysql_query($sql2,conectar::con());
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];
		
		
        $sql=<<<QUERY
			
		SELECT id_proyecto, nombre_proyecto, fecha_inicio, fecha_compromiso, id_cliente, Estado 
		$from  
        
		ORDER BY $jtSorting
		$limit;		
QUERY;

        $msgerror="";
		try
		{  $result = mysql_query($sql,conectar::con());	} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "Proyectos :: listar :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "Proyectos :: listar :: SQLERROR -> $msgerror -> $sql";};
		
		
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
		
		if (!$PERMISOS_ALMACENES['CREAR']=='1'){
		
			$jTableResult = array();
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message']= "Proyectos :: CREAR :: Acceso denegado.";
			$jTableResult['TotalRecordCount'] = 0;
			$jTableResult['Records'] = array();
			
			print json_encode($jTableResult);
			die;
			
		}

		//Insert record into database
		
		$nombre_proyecto=$_POST["nombre_proyecto"];
		$id_cliente=$_POST["id_cliente"];
		$f_inicio=$_POST["fecha_inicio"];
		$fecha_inicio=date('Y-m-d', strtotime(str_replace("/","-",$f_inicio)));
		$f_fin = $_POST["fecha_compromiso"];
		$fecha_compromiso=date('Y-m-d', strtotime(str_replace("/","-",$f_fin)));
		$Estado=$_POST["Estado"];
 
		$sql=<<<QUERY
		INSERT INTO proyectos(
   id_proyecto
  ,nombre_proyecto
  ,fecha_inicio
  ,fecha_compromiso
  ,id_cliente
  ,Estado
) VALUES (
   NULL
  ,'$nombre_proyecto'  
  ,'$fecha_inicio'  
  ,'$fecha_compromiso'  
  ,'$id_cliente'   
  ,'$Estado'  
);



		
QUERY;

		$msgerror="";
		try
		{  $result = mysql_query($sql,conectar::con());} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "Proyectos :: Ingresar :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "Proyectos :: Ingresar :: SQLERROR -> $msgerror -> $sql";};
		
					
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

		if (!$PERMISOS_ALMACENES['MODIFICAR']=='1'){
		
			$jTableResult = array();
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message']= "Proyectos :: MODIFICAR :: Acceso denegado.";
			$jTableResult['TotalRecordCount'] = 0;
			$jTableResult['Records'] = array();
			
			print json_encode($jTableResult);
			die;
			
		}


	    $id_proyecto=$_POST["id_proyecto"];
	    $nombre_proyecto=$_POST["nombre_proyecto"];
	    $f_inicio=$_POST["fecha_inicio"];
	    $fecha_inicio=date('Y-m-d', strtotime(str_replace("/","-",$f_inicio)));
	    $f_fin =$_POST["fecha_compromiso"];
	    $fecha_compromiso=date('Y-m-d', strtotime(str_replace("/","-",$f_fin)));
	    $id_cliente=$_POST["id_cliente"];
        $Estado=$_POST["Estado"];
   
	
		$sql=<<<QUERY

		UPDATE proyectos
SET

  nombre_proyecto = '$nombre_proyecto' 
  ,fecha_inicio = '$fecha_inicio'
  ,fecha_compromiso = '$fecha_compromiso'
  ,id_cliente = '$id_cliente'
  ,Estado = '$Estado'
WHERE id_proyecto  = '$id_proyecto';
		
QUERY;
		
		
		
		//die($sql);
		
		$msgerror="";
		try
		{ $result = mysql_query($sql,conectar::con());} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "Proyectos :: Modificar :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "Proyectos :: Modificar :: SQLERROR -> $msgerror -> $sql";};
		


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = $vRESP;
		$jTableResult['Message']= $vMENSAJE;
		print json_encode($jTableResult);
		
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{

		if (!$PERMISOS_ALMACENES['ELIMINAR']=='1'){
		
			$jTableResult = array();
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message']= "Proyectos :: ELIMINAR :: Acceso denegado.";
			$jTableResult['TotalRecordCount'] = 0;
			$jTableResult['Records'] = array();
			
			print json_encode($jTableResult);
			die;
			
		}
		
    	$id_proyecto=$_POST["id_proyecto"];

		$delete=<<<QUERY
		UPDATE  proyectos SET  Estado =  'inactivo' WHERE  proyectos.id_proyecto ='$id_proyecto';
QUERY;

		$msgerror="";
		try
		{ $result = mysql_query($delete,conectar::con());} 
        catch(Exception $ex){	$result=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($result)
		{ 	$vRESP="OK"; $vMENSAJE = "Proyectos :: Eliminada :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "Proyectos :: Eliminada :: SQLERROR -> $msgerror -> $sql";};


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = $vRESP;
		$jTableResult['Message']= $vMENSAJE;
		print json_encode($jTableResult);
	}

	else if($_GET["action"] == "clientes")
	{
		$sqlprov=<<<QUERY
        
        SELECT customers.IDCliente, 
		customers.rut, 
		customers.Cliente
		FROM customers;
QUERY;

		$msgerror="";
		
        try
		{ 
		  
          $resultsql = mysql_query($sqlprov,conectar::con());
          while($row=mysql_fetch_assoc($resultsql))
    	  {
    			$resoptions[]=array("DisplayText"=>$row["Cliente"],"Value"=>$row["IDCliente"]);
    	  }
            	        
        } 
        catch(Exception $ex){	$resultsql=0; $msgerror=$ex;}
		
		$vRESP=$result;
		if ($resultsql)
		{ 	$vRESP="OK"; $vMENSAJE = "Clientes :: cargar :: OK!";	}
		else
		{	$vRESP="ERROR"; $vMENSAJE = "Clientes :: cargar :: SQLERROR -> $msgerror -> $sqlprov";};

	    $result = array();
		$result['Result'] = $vRESP;
		$result['Message']= $vMENSAJE;
		$result['Options']= $resoptions;

        print json_encode($result);
        
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
