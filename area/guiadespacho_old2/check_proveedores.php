<?php


require_once("../validatesession_html.php");


$ACTUAL_THEME=$_SESSION['SESS_ACTUAL_THEME'];
$JTABLE_THEME=$_SESSION['SESS_JTABLE_THEME'];

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Cheklist Pago Proveedores Masivo</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

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

<body class="ui-widget">

<h4>Cheklist Pago Proveedores Masivo</h4>

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

				    <td><input type="text" id="bsqrut" name="bsqrut" style="width:100%" placeholder="Buscar por RUT o Nombre Proveedor" class="form-control input-sm ui-corner-all"></td>
				</tr>
			    </tbody>
			</table>
		    </td>

		    
		    <td width="20%" align="center">
			<button style="height: 30px; width: 100px" aria-disabled="false" role="button"
				class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"
				id="btnBUSCAR" type="submit">
		    <span class="ui-icon ui-icon-search"></span>
		    <span class="ui-button-text">Buscar</span>
			</button>
		    </td>
		</tr>
	    </tbody>
	</table>
    </form>
</div>

<div style="height: 5px;width: 100%;"></div>

<div id="jt_pago_proveedores" style="width: 100%;"></div>

<div id="documentar" title="Documentar">
    <p class="prov"></p>
    <label>Pagos asociados a cuenta de <strong>BANCO CHILE</strong></label>
    <label>Cantidad Cheques: </label>
    <select name="cantidad_cheques" class="form-control" id="cantidad_cheques">
    	<option value="1">1</option>
    	<option value="2">2</option>
    	<option value="3">3</option>
    	<option value="4">4</option>
    	<option value="5">5</option>
    	<option value="6">6</option>
    	<option value="7">7</option>
    	<option value="8">8</option>
    	<option value="9">9</option>
    	<option value="10">10</option>
    	<option value="11">11</option>
    	<option value="12">12</option>    	
    </select><br>
    <label>Valor por cada Cheque: </label>
    <label id="valorCheque" class="form-control"></label><br>
    <label>Tipo de Compromiso: </label>
	<select name="tipo_documento" class="form-control" id="tipo_documento">
		<option value="Documento">Documento</option>
		<option value="Efectivo/Transferencia">Efectivo/Transferencia</option>
		<option value="Transferencia/Efectivo">Transferencia/Efectivo</option>
	</select><br>
    <div id="cheques">
    	<label>Fecha de Pago Cheque 1 : </label><input type='text' class="form-control" id="c1"/>
	</div><br>
	<div id="voucher">
    	<label>Comprobante 1 : </label><input type='text' class="form-control" id="v1"/>
	</div><br>

</div>

<div id="error" title="Documentar">

</div>


<style>

form.jtable-dialog-form { width:500px; top:0px}

</style>        
<script type="text/javascript">

	$(document).ready(function() {


		//variable verificadora
		verificar = true;

	    $('#jt_pago_proveedores').jtable({
		jqueryuiTheme: true,
		title: 'Listado General',
		paging: true,
		pageSize: 10,
		sorting: true,
		defaultSorting: 'RUT ASC',
		selecting: true,
		multiselect: true,
		selectingCheckboxes: true,
		actions: {
		    listAction: 'check_proveedores_sql.php?action=list'		   
		},
		toolbar: {
		    items: [{
			icon: '../toolbar-icon/pago.gif',
			text: 'Documentar Cheques',
			click: function() {

			    var $selectedRows = $('#jt_pago_proveedores').jtable('selectedRows');

			    if ($selectedRows.length > 0) {
			    	if(verificar==true)
			    	{
			    		$('.prov').html("");
			    		$('#valorCheque').html("");
						$('.prov').append("Total de facturas Seleccionadas <strong>" + $selectedRows.length + "</strong>");
						$('#valorCheque').append(Moneda(totsum.toString()));
						$('.prov').append("<br>Valor Total Facturas <strong> $ " + Moneda(totsum.toString()) + "</strong>");
						
						$("#documentar").dialog("open");
			    	}else{
			    		$('#error').html("");
						$('#error').append(" <p>Debes Seleccionar el mismo proveedor</p>");
			    		$("#error").dialog("open");
			    	}
			   
			    } else {
				alert("Debe seleccionar alguna factura.");
			    }

			}
		    }]
		},
		fields: {
		    id_ecompra: {
			key: true,
			create: false,
			edit: false,
			list: false
		    },
		    RUT: {
			title: 'Rut',
			width: '10%',
			list: true

		    },
		    Suppliers: {
			title: 'Proveedores',
			width: '30%',
			list: true
		    },
		    folio_factura: {
			title: 'Factura',
			width: '30%',
			list: true
		    },
		    contador: {
			title: 'Contador',
			width: '20%',
			list: true
		    },
		    total: {
			title: 'Total',
			width: '40%',
			list: true
		    },
		    IDsuppliers: {
			title: 'IDsuppliers',
			width: '60%',
			list: false
		    },
			

		},
		selectionChanged: function () {
                //Get all selected rows
                var $selectedRows = $('#jt_pago_proveedores').jtable('selectedRows');

                
 				
                $('#SelectedRowList').empty();
                cont = 0;
                rutp = 0;
                totsum = 0;

                 if ($selectedRows.length > 0) {	                    
                    
                    $selectedRows.each(function () {


                    	var record = $(this).data('record');

                    	totsum = parseInt(record.total) + parseInt(totsum); 
                    	$('.total_suma').text(totsum);

                        
                        if(cont==0)
                        {
                        	rutp = record.RUT;
                        	verificar = true;
                        }else if(rutp != record.RUT)
                        {                        	
                        	verificar = false;
                        	return false;
                        }else{
                        	verificar = true;
                        }

                        cont++;
            		});
                }
        },
		formCreated: function(event, data) {

		    $(".ui-dialog").css("top", "0px");
		    
		    $("#ui-id-5, #ui-id-3").css('height', '350px').css('top', '0px').css('overflow-y', 'scroll').css('overflow-x', 'hidden');
		    $("#EditDialogSaveButton, #AddRecordDialogSaveButton").attr('class', 'btn btn-primary ui-corner-all');

		    $("form input, form textarea, form select").attr('class', 'form-control input-sm ui-corner-all');


		    $("#Edit-rut").css('width', '150px').attr('title', 'Ingrese rut valido');

		    $("#Edit-Cliente").css('width', '250px');

		    $("#Edit-Empresa").css('width', '250px');

		    $("#Edit-DomicilioEmpresa").css('width', '350px');

		    $("#Edit-DomicilioDespacho").css('width', '350px');

		    $("#Edit-Telefono").css('width', '200px');

		    $("#Edit-Web").css('width', '250px');

		    $("#Edit-EmailContacto").css('width', '250px');

		    $("#Edit-TelefonoContacto").css('width', '250px');

		    $("#Edit-Contacto").css('width', '250px');

		    $("#Edit-Giro").css('width', '400px');

		    $("#Edit-TwitterContacto").css('width', '250px');

		    $("#Edit-IDComuna").css('width', '250px');

		    $("#Edit-IDFormaPago").css('width', '250px');


		   
		    
		},

		

	    });
	

		
	    $('#jt_pago_proveedores').jtable('load', undefined, function(){    

	    		

				/*
	    		$(".jtable-main-container tr").find("td:last").each(function () {
					    $(this).text(Moneda($(this).text()));
				});
*/

	    
    			
		});

		
		
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


	    
	    $('#btnBUSCAR').on('click', function(e) {
		e.preventDefault();

		$('#jt_pago_proveedores').jtable('load', {
		    bsqrut: $('#bsqrut').val()
		})
	    });

	    $("#documentar").dialog({
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
				$("#cantidad_cheques").val(1);
				totsum = 0;
				$("#jt_pago_proveedores").find(".jtable-selecting-column > input").prop("checked", false);
				$("#jt_pago_proveedores tr").removeClass("jtable-row-selected ui-state-highlight");
				$("#cheques").html("");
				$("#voucher").html("");
				$("#cheques").append('<label>Fecha de Pago Cheque 1 : </label><input type="text" class="form-control" id="c1"/>');
				$("#voucher").append('<label>Comprobante 1 : </label><input type="text" class="form-control" id="v1"/>');
				$( "#c1").datepicker({dateFormat: 'dd-mm-yy'});
		    },
		    "Documentar": function() {
				var $selectedRows = $('#jt_pago_proveedores').jtable('selectedRows');



				fechaCompromiso = "";
				codigoVoucher ="";
				
				var cantChe2 = parseInt($("#cantidad_cheques").val()) + parseInt(1);
	    		for(var i=1;i<cantChe2;i++)
				{
					
						fechaCompromiso += $("#c"+i).val()+',';
						codigoVoucher += $("#v"+i).val()+',';
						
					
				}

				idEcompra = "";
				montoCuota = parseInt(totsum) / parseInt($("#cantidad_cheques").val()).toFixed(0);
				tipo_compromiso = $("#tipo_documento").val();
				IDsuppliers = "";
				IDUser = <?=$_SESSION['SESS_USER_ID']?>;
				TotalCheques = $("#cantidad_cheques").val();
				

				$selectedRows.each(function () {
					var record = $(this).data('record');					
					idEcompra+= record.id_ecompra+",";
					IDsuppliers += record.IDsuppliers+",";
				});


				$.ajax({
					  cache: false,
					  type: "POST",
					  url: "./check_proveedores_sql.php?action=documentar",
					  data: {"idEcompra" : idEcompra, "fechaCompromiso": fechaCompromiso, "IDsuppliers" : IDsuppliers, "montoCuota": montoCuota, "tipo_compromiso": tipo_compromiso, "IDUser": IDUser, "TotalCheques": TotalCheques,"codigoVoucher":codigoVoucher},
				      success: function(data){
				      //	alert(data.codigoVoucher);
				  	 	$('#jt_pago_proveedores').jtable('load');
				  	 	//muestra la query en pantalla
				  	 	//alert(data);

			          }
				});
				$(this).dialog("close");
				$("#cantidad_cheques").val(1);
				totsum = 0;
		    }
		}
	    });

	    $("#error").dialog({
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
		    "Cerrar": function() {
			$(this).dialog("close");
		    }		    
		}
	    });


	    $("#cantidad_cheques").on("change", function(){
	    	$('#valorCheque').html("");
	    	$('#valorCheque').append(Moneda((totsum / $(this).val()).toFixed(0)).toString());

	    	alert("Los cheques son de : $ "+Moneda((totsum / $(this).val()).toFixed(0)));
	    	
	    	$("#cheques").html("");
	    	$("#voucher").html("");
	    	var cantChe = parseInt($(this).val()) + parseInt(1);
	    	for(var i=1;i<cantChe;i++)
	    	{	    		
	    		$("#cheques").append("<label>Fecha de Pago Cheque "+i+" : </label><input type='text' class='form-control' id='c"+i+"' /><br>");
	    		$("#voucher").append("<label>Comprobante "+i+" : </label><input type='text' class='form-control' id='v"+i+"' /><br>");

	    		$( "#c"+i).datepicker({dateFormat: 'dd-mm-yy'});
	    	}
	    });

	    $( "#c1").datepicker({dateFormat: 'dd-mm-yy'});

	    

		

	});
</script>




</body>
</html>