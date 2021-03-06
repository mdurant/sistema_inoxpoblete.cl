<?php
require_once("../validatesession_html.php");

$ACTUAL_THEME=$_SESSION['SESS_ACTUAL_THEME'];
$JTABLE_THEME=$_SESSION['SESS_JTABLE_THEME'];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Gestion interna</title>

    <!-- bootstrap -->
    <script src="../scripts/bootstrap/js/bootstrap.js" type="text/javascript"></script>
    <link  href="../scripts/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    
	<!-- jquery -->
	<script src="../scripts/jquery/jquery.js" type="text/javascript"></script>
    <script src="../scripts/jquery/jquery-ui.js" type="text/javascript"></script>
    <link  href="../scripts/jquery/themes/<?=$ACTUAL_THEME?>/jquery-ui.css" rel="stylesheet" type="text/css" />
	
    <!-- jtable -->
	<script src="../scripts/jtable/jquery.jtable.js" type="text/javascript"></script>
    <script src="../scripts/jtable/jquery.jtable.es.js" type="text/javascript"></script>
    <link  href="../scripts/jtable/themes/<?=$JTABLE_THEME?>" rel="stylesheet" type="text/css" />
    
    <!-- jquery.validationEngine -->
    <script src="../scripts/jquery/validate/jquery.validationEngine.js" type="text/javascript" ></script>
    <script src="../scripts/jquery/validate/jquery.validationEngine-es.js" type="text/javascript"></script>
    <link  href="../scripts/jquery/validate/validationEngine.jquery.css" rel="stylesheet" type="text/css" />


</head>
<style>

form.jtable-dialog-form { width:500px; }

</style>  
<body class="ui-widget">
<h4>Sucursales</h4>

<div class="ui-widget-content ui-corner-all" style="width: 100%; height: 45px">
    <form style="margin: 0px">
	<table width="65%" cellspacing="2" cellpadding="4">
	    <tbody>
		<tr>
		    <td width="60%">
			<table width="100%">
			    <tbody>
				<tr>
				    <td>
					<h5 style="width:30px">Buscar</h5>
				    </td>

				    <td><input type="text" id="nombresucursal" name="nombresucursal" style="width:100%" placeholder="" class="form-control input-sm ui-corner-all"></td>
				</tr>
			    </tbody>
			</table>
		    </td>

		    <td width="20%" align="center">
			<table style="width:130px">
			    <tbody>
				<tr>
				    <td>
					<h5>Incluir Inactivos</h5>
				    </td>

				    <td><input type="checkbox" style="vertical-align: middle;" value="1" name="chkinactivo" id="chkinactivo"></td>
				</tr>
			    </tbody>
			</table>
		    </td>

		    <td width="20%" align="center"><button style="height: 30px; width: 100px" aria-disabled="false" role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="btnBUSCAR" type="submit"><span class="ui-button-text">Buscar</span></button></td>
		</tr>
	    </tbody>
	</table>
    </form>
</div>

<div style="height: 5px;width: 100%;"></div>

<div id="jt_sucursal" style="width: 100%;"></div>


        
	<script type="text/javascript">
		$(document).ready(function() {
		    //Prepare jTable
		    $('#jt_sucursal').jtable({
			jqueryuiTheme: true,
			title: 'Listado',
			paging: true,
			pageSize: 10,
			sorting: true,
			defaultSorting: 'IDSucursal ASC',
			selecting: true,
			//Enable selecting
			multiselect: true,
			//Allow multiple selecting
			selectingCheckboxes: true,
			//Show checkboxes on first column
			//selectOnRowClick: true, //Enable this to only select using checkboxes
			toolbar: {
			    items: [{
				icon: '../toolbar-icon/delete.png',
				text: 'Eliminar',
				click: function() {

				    var $selectedRows = $('#jt_sucursal').jtable('selectedRows');

				    if ($selectedRows.length > 0) {
					$('#dialog').html(" <p>Desea eliminar " + $selectedRows.length + " registros</p>");
					$("#dialog").dialog("open");
				    } else {
					alert("Debe seleccionar registros para eliminar.");

				    }

				}
			    }]
			},
			actions: {
			    listAction: 'sucursales_sql.php?action=list',
			    createAction: 'sucursales_sql.php?action=create',
			    updateAction: 'sucursales_sql.php?action=update',
			    deleteAction: 'sucursales_sql.php?action=delete'
			},
			fields: {
			    IDSucursal: {
				key: true,
				create: false,
				edit: false,
				list: false
			    },
			    Nombre: {
				title: 'Nombre',
				width: '20%',
				create: true,
				edit: true,
				list: true

			    },
			    Domicilio: {
				title: 'Domicilio',
				width: '20%',
				create: true,
				edit: true,
				list: true

			    },
			    Email: {
				title: 'Email',
				width: '20%',
				type: 'email',
				create: true,
				edit: true,
				list: true
			    },
			    IDempresa: {
				title: 'Empresa',
				width: '20%',
				options: 'sucursales_sql.php?action=empresa',
				create: true,
				edit: true,
				list: true
			    },
			    Estado: {
				title: 'Estado',
				width: '20%',
				options: {
				    "inactivo": "inactivo",
				    "activo": "activo"
				},
				create: true,
				edit: true,
				list: true
			    }



			},
			formCreated: function(event, data) {
			    $("div[aria-describedby='ui-id-5']").css("top", "0px");
			    $("div[aria-describedby='ui-id-3']").css("top", "0px");
			    $("#ui-id-5, #ui-id-3").css('height', '350px').css('overflow-y', 'scroll').css('overflow-x', 'hidden');
			    $("#EditDialogSaveButton, #AddRecordDialogSaveButton").attr('class', 'btn btn-primary');
			    $("form input[type=text], form textarea, form select").attr('class', 'form-control input-sm ui-corner-all');

			},

			//selectrows
			selectionChanged: function() {

			}
		    });


		    //Load person list from server
		    $('#jt_sucursal').jtable('load');

		    //buscar por clientes
		    $('#btnBUSCAR').on('click', function(e) {
			e.preventDefault();
			$('#jt_sucursal').jtable('load', {
			    nombresucursal: $('#nombresucursal').val(),
			    inactivo: $('#chkinactivo').val()
			});
		    });
		    //eliminar
		    // $('#DeleteAllButton').click(function () {
		    //         var $selectedRows = $('#PeopleTableContainer').jtable('selectedRows');
		    //         $('#PeopleTableContainer').jtable('deleteRows', $selectedRows);
		    //     });




		    //Initialize validation logic when a form is created

		    $("#dialog").dialog({
			autoOpen: false,
			show: {
			    effect: "fade",
			    duration: 500
			},
			hide: {
			    effect: "fade",
			    duration: 500
			},
			modal: true,
			buttons: {
			    "Cancelar": function() {

				$(this).dialog("close");
			    },
			    "Eliminar": function() {
				var $selectedRows = $('#jt_sucursal').jtable('selectedRows');
				$('#jt_sucursal').jtable('deleteRows', $selectedRows);
				$(this).dialog("close");
			    }
			}
		    });





		    $("#dialog2").dialog({
			autoOpen: false,
			show: {
			    effect: "fade",
			    duration: 500
			},
			hide: {
			    effect: "fade",
			    duration: 500
			}
		    });

		    $('#chkinactivo').on('change', function() {
			// From the other examples
			if (!this.checked) {
			    $('#chkinactivo').val("1");
			} else {
			    $('#chkinactivo').val("2");
			}
		    });



		});</script>

	<div id="dialog" title="Basic dialog">
  <p>Desea eliminar</p>
</div>

<div id="dialog2">
<div id="jt_prueba" style="width: 1200px;"></div>
</div>

</body>
</html>