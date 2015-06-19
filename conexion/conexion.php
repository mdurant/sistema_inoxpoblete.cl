<?php
/*
** Clase conexion para el proyecto
funcion: con();
*/

class conectar{
    
   
public static function con()
{
	$conx=mysql_connect("localhost","inoxpoblete","9v8nbR69c3Yb4q2j")or die("Error Server xxxxxxxx");
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db("inoxpoblete",$conx);
	return $conx;
}
public static function desconectar()
{
	mysql_close(conectar::con());
}

} 

?>
