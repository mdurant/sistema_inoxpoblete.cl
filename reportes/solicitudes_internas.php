<?php
 
//Import the PhpJasperLibrary
include_once("tcpdf/tcpdf.php");
include_once("PHPJasperXML.inc.php");

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
 
 
//database connection details
 
$server="localhost";
$db="inoxpoblete";
$user="inoxpoblete";
$pass="9v8nbR69c3Yb4q2j";
$version="0.8b";
$mysqlport=3306;
$pchartfolder="./pchart2";

// variable para capturar

$contador_solicitud = $_GET["contador"];
 

 
//display errors should be off in the php.ini file
ini_set('display_errors', 0);
 
//setting the path to the created jrxml file
$xml =  simplexml_load_file("rp_solicitudes_internas.jrxml");
 

 
 
$PHPJasperXML = new PHPJasperXML();
$PHPJasperXML->debugsql=false;
$PHPJasperXML->arrayParameter=array("contador_esolicitud"=>$contador_solicitud);
$PHPJasperXML->xml_dismantle($xml);
 
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file
 
 
?>
