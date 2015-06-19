<?php
require_once("../validatesession_html.php");

$ACTUAL_THEME=$_SESSION['SESS_ACTUAL_THEME'];
$JTABLE_THEME=$_SESSION['SESS_JTABLE_THEME'];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"/>
<title>Gestión Compra de Combustible</title>


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

    <!-- jquery-barcode -->
	<script type="text/javascript" src="../js/jquery-barcode.js"></script>
    

</head>
<style>

form.jtable-dialog-form {
  width:450px;
}
#btn
{
	cursor: pointer;
}



</style>
<body  class="ui-widget">


<h4>Gestión Compra de Combustible</h4>
    
<div class="ui-widget-content ui-corner-all" style="width: 100%; height: 45px ">

    <form style="margin: 0px">
	
	
    <table width="65%" cellspacing="2" cellpadding="4">
	    <tbody><tr>
		<td width="60%">
		    <table width="100%">
			<tbody><tr>
			    <td><h5 style="width:30px">Buscar </h5></td>
			    <td><input type="text" id="proveedor" name="proveedor" style="width:100%" placeholder="" class="form-control input-sm ui-corner-all "></td>
			</tr>
		    </tbody></table>
		    
		</td>
		<td width="20%" align="center">
		    
		    <table style="width:130px">
			<tbody><tr>
			    <td><h5>Incluir Inactivos </h5></td>
			    <td><input type="checkbox" style="vertical-align: middle;" value="1" name="chkinactivo" id="chkinactivo"></td>
			</tr>
		    </tbody></table>
		    
		</td>
		<td width="20%" align="center">
		    <button style="height: 30px; width: 100px" aria-disabled="false" role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="btnBUSCAR" type="submit">
		    <span class="ui-icon ui-icon-search"></span>
		    <span class="ui-button-text">Buscar</span>
		</button></td>
	    </tr>
	</tbody>
	</table>



    </form>
</div>
<div style="height: 5px;width: 100%;"></div>


<div id="jt_combustible" style="width: 100%;"></div>

        
<script type="text/javascript">
		$(document).ready(function() {
		  //Prepare jTable
		  $('#jt_combustible').jtable({
			jqueryuiTheme: true,
			title: 'Listado',
			paging: true,
			pageSize: 10,
			sorting: true,
			defaultSorting: 'id_compra_combustible ASC',
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

				  var $selectedRows = $('#jt_combustible').jtable('selectedRows');

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
			  listAction: 'combustible_sql.php?action=list',
			  createAction: 'combustible_sql.php?action=create',
			  updateAction: 'combustible_sql.php?action=update',
			  deleteAction: 'combustible_sql.php?action=delete'
			},
			fields: {
			  id_compra_combustible: {
				key: true,
				create: false,
				edit: false,
				list: false
			  },
			  
			 numero_documento: {
				title: 'Nº Docto.',
				width: '5%',
				create: true,
				edit: false,	
				list: true

			  },
			  tipo_documento: {
				title: 'Tipo ',
				width: '5%',
				create: true,
				edit: false,
				list: true,
				options: {
			    'GUIA DESPACHO': 'GUIA DESPACHO',
			    'FACTURA': 'FACTURA'
				}

			  },
			  id_proveedor: {
				title: 'Proveedor',
				width: '8%',
				create: true,
				options: 'combustible_sql.php?action=proveedor',
				edit: false,
				list: true
	
			  },
			  id_empresa: {
				title: 'Empresa',
				width: '10%',
				create: true,
				options: 'combustible_sql.php?action=empresa',
				edit: false,
				list: true
	
			  },
			  fecha_emision: {
				title: 'Fecha Emisión',
				width: '5%',
				type: 'date',
        displayFormat: 'dd-mm-yy',
				create: true,
				edit: false,
				list: true
			  },
			  glosa: {
				title: 'Descipción',
				width: '20%',
				create: true,
				edit: false,
				list: false
			  },
			forma_pago: {
				title: 'Forma Pago',
				width: '5%',
				create: true,
				options: 'combustible_sql.php?action=forma_pago',
				edit: false,
				list: true
	
			  },
				/*
			  neto: {
				title: 'Total Neto',
				width: '10%',
				create: true,
				edit: false,
				list: true
			},
			  excento: {
				title: 'Total Excento',
				width: '10%',
				create: true,
				edit: false,
				list: true
			  },
			  impuesto_especifico: {
				title: 'Impuesto Específico',
				width: '13%',
				create: true,
				edit: false,
				list: true
			  },
				
			  iva: {
				title: 'I.V.A.',
				width: '10%',
				create: true,
				edit: false,
				list: true
			  },
			  */
			  total: {
				title: 'Total $',
				width: '8%',
				create: true,
				edit: false,
				list: true
			  },
				
				numero_documento: {
            title: 'Asociar',
            width: '1%',
            sorting: false,
            edit: false,
            create: false,
              display: function(datos) {
              //Create an image that will be used to open child table
                var $img = $('<center><button title="Vincular a Proyecto" class="btn btn-default btn-sm ui-corner-all" type="button" ><span class="glyphicon glyphicon-link"></span></button></center>');

                $img.on("click", function() {
									$("#fol").val(datos.record.numero_documento);
                  $("#dialogo_proyecto").dialog("open");
                  $("#id_ots").val(datos.record.id_compra_combustible);

                });
							return $img;
                 }
            },


			},
			formCreated: function(event, data) {

			  $("div[aria-describedby='ui-id-5']").css("top", "0px");
			  $("div[aria-describedby='ui-id-3']").css("top", "0px");

			  $("#ui-id-5, #ui-id-3").css('height', '350px').css("width", "580px").css('overflow-y', 'scroll').css('overflow-x', 'hidden');
			  $("#EditDialogSaveButton, #AddRecordDialogSaveButton").attr('class', 'btn btn-primary');
			  $("form input[type=text], form textarea, form select").attr('class', 'form-control input-sm ui-corner-all');

			  
			  
			  //validacion
			  data.form.find('input[name="CodeBar"]').addClass('validate[required]');
			  data.form.find('input[name="ProductName"]').addClass('validate[required]');
			  data.form.find('input[name="UnidadMedida"]').addClass('validate[required]');
			  data.form.find('input[name="IDCellar"]').addClass('validate[required]');
			  data.form.find('input[name="Description"]').addClass('validate[required]');
			  data.form.find('input[name="Suppliers"]').addClass('validate[required]');
			  data.form.find('input[name="CategoryProduct"]').addClass('validate[required]');
			  data.form.find('input[name="Nombre"]').addClass('validate[required]');
			  data.form.validationEngine();
			},
			//Validate form when it is being submitted
			formSubmitting: function(event, data) {
			  return data.form.validationEngine('validate');
			},
			//Dispose validation logic when form is closed
			formClosed: function(event, data) {
			  data.form.validationEngine('hide');
			  data.form.validationEngine('detach');
			},

			recordsLoaded: function(event, data) {

                function Moneda(entrada){
                    var num = entrada.replace(/\./g,"");
                    if(!isNaN(num)){
                    num = num.toString().split("").reverse().join("").replace(/(?=\d*\.?)(\d{3})/g,"$1.");
                    num = num.split("").reverse().join("").replace(/^[\.]/,"");
                    entrada = num;
                    }else{
                    entrada = input.value.replace(/[^\d\.]*/g,"");
                    }
                    return entrada;
                }

            $(".jtable-main-container tr").find("td:eq(7)").each(function () {
                        $(this).text(Moneda($(this).text()));
            });
            $(".jtable-main-container tr").find("td:eq(8)").each(function () {
                        $(this).text(Moneda($(this).text()));
            });
            $(".jtable-main-container tr").find("td:eq(9)").each(function () {
                        $(this).text(Moneda($(this).text()));
            });
            $(".jtable-main-container tr").find("td:eq(10)").each(function () {
                        $(this).text(Moneda($(this).text()));
            });
            $(".jtable-main-container tr").find("td:eq(11)").each(function () {
                        $(this).text(Moneda($(this).text()));
            });
        },


              });


		  //Load person list from server
		  $('#jt_combustible').jtable('load');

		  //buscar por clientes
		  $('#btnBUSCAR').on('click', function(e) {
			e.preventDefault();
			$('#jt_combustible').jtable('load', {
			  nombreproducto: $('#nombreproducto').val(),
			  inactivo: $('#chkinactivo').val()
			});
		  });
		 
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
				var $selectedRows = $('#jt_combustible').jtable('selectedRows');
				$('#jt_combustible').jtable('deleteRows', $selectedRows);
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

		  // dialogo proyecto
			//dialogos
        $("#dialogo_proyecto").dialog({
            autoOpen: false,
            width: 300,
            heigth: 300,
            position: 'top',
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
                  "Guardar": function() {

                        var datasrl = $("#formularios").serialize();
                        $.ajax({

                              async: true,
                              cache: false,
                              type: "POST",
                              dataType: "json",
                              url: "asociar_folio.php",
                              data: datasrl,
                              success: function(response) {
                                    alert("Se Guardo Correctamente");
                                      $("#dialogo_proyecto").dialog("close");
                                      $("#fol").val("");
                                      $('#jt_ordenes').jtable('reload');
                              }

                        });


                  },
                  "Cerrar": function() {
                        $(this).dialog("close");

                  }
            }
      });

		});



</script>



<div id="dialog" title="Basic dialog">
  <p>Desea eliminar</p> </div>
  
  <div id="dialog2">
  <div id="jt_prueba" style="width: 1200px;"></div>
</div>
  
	<!-- asociar proyecto -->

 <div id="dialogo_proyecto" title="Datos para Vincular a Servicios/Materiales" style="width: 100%; display:none">
<?php
require_once("../area/servicios_a_proyectos/select_guia.php");

$datos = new funciones();
$orden= $datos->proyectos();
?>
    <form id="formularios" method="post">
        <br />
        
					<label style="float: left;">Proyecto: </label><br>
				<select name="proyecto" id="proyecto">
					<option value="0">-Seleccione-</option>
					 <?php
             for($a=0;$a<sizeof($orden);$a++)
               {
            ?>
          <option value="<?=$orden[$a]["id_proyecto"]?>"><?=strtoupper($orden[$a]["nombre_proyecto"])?></option>
          <?php
							}
           ?>
				</select>
        <input type="hidden" name="id_ots" id="id_ots" />
    </form>

</div> 
  <!-- fin asociar proyecto -->

		
</body>
</html>