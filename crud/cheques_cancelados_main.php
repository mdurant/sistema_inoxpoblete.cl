<?php
require_once("../validatesession_html.php");

$ACTUAL_THEME=$_SESSION['SESS_ACTUAL_THEME'];
$JTABLE_THEME=$_SESSION['SESS_JTABLE_THEME'];

if (empty($_SESSION["FAC_FECHABUSQUEDA1"])) { $_SESSION["FAC_FECHABUSQUEDA1"] = date("d-m-Y");  }
    if (empty($_SESSION["FAC_FECHABUSQUEDA2"])) { $_SESSION["FAC_FECHABUSQUEDA2"] = date("d-m-Y");  }

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

form.jtable-dialog-form {
  width:300px;
}
#btn
{
	cursor: pointer;
}



</style>
<body class="ui-widget">

<h4>Historial Cheques Cancelados</h4>
<div class="ui-widget-content ui-corner-all" style="width: 100%; height: 45px ">
    <form style="margin: 0px">
    <table width="65%" cellspacing="2" cellpadding="4">
	    <tbody><tr>
		<td width="60%">
		     <table style="width:100%">
            <tbody><tr>
                  <td style="width: 50px;text-align: center"><h5 style="width:50px">Desde</h5></td>
                  <td><input class="form-control input-sm ui-corner-all " type="text" name="inicio" id="inicio" /></td>
                  <td style="width: 50px;text-align: center"><h5 style="width:50px">Hasta</h5></td>
                  <td><input class="form-control input-sm ui-corner-all " type="text" name="fin" id="fin" /></td>
                  <td align="center">
                    
                    
                  </td>
            </tr>
            </tbody></table>
		    
		</td>
		
		<td width="20%" align="center">
		    
		    
		</td>
		<td width="20%" align="center">
		    <button style="height: 30px; width: 100px" aria-disabled="false" role="button"
			    class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary"
			    id="btnBUSCAR" type="submit">
		    <span class="ui-icon ui-icon-search"></span>
		    <span class="ui-button-text">Buscar</span>

		</button></td>
	    </tr>
	</tbody>
    </table>
    </form>
</div>
<div style="height: 5px;width: 100%;"></div>

 
<div id="jt_cheques_cancelados" style="width: 100%;"></div>

        
<script type="text/javascript">

$('#inicio').attr('value', '<?php echo $_SESSION["FAC_FECHABUSQUEDA1"]; ?>');
    $('#fin').attr('value', '<?php echo $_SESSION["FAC_FECHABUSQUEDA2"]; ?>');

    $("#inicio").datepicker({
      dateFormat: 'dd-mm-yy'
    });
    $("#fin").datepicker({
      dateFormat: 'dd-mm-yy'
    });

    $(document).ready(function () {
	//Prepare jTable
	    $('#jt_cheques_cancelados').jtable({
		    jqueryuiTheme: true,
		    title: 'Listado',
		    paging: true,
		    pageSize: 10,
		    sorting: true,
		    defaultSorting: 'id_compra ASC',
		    selecting: true, //Enable selecting
		    multiselect: false, //Allow multiple selecting
		    selectingCheckboxes: false, //Show checkboxes on first column
		    //selectOnRowClick: true, //Enable this to only select using checkboxes
		    toolbar: {
			  
		    },
		    actions: {
			    listAction:	  'cheques_cancelados_sql.php?action=list'
			   
		    },
		    fields: {
			    id_compra: {
				    key: true,
				    create: false,
				    edit: false,
				    list: false
			    },
			    tipo_compromiso:
			    {
				    title: 'Tipo Compromiso',
				    width: '10%',
				    create: true,
				    edit: true,
				    list: true

			    },
			    voucher:
			    {
				    title: 'Cheques Asociados',
				    width: '30%',
				    create: true,
				    edit: true,
				    list: true

			    },
			    fecha_pago: {
				    title: 'Fecha Pago',
				    width: '15%',
				    create: true,
				    edit: true,
				    list: true,
				    type: 'date',
				    displayFormat: 'dd-mm-yy',

			    },
			    folio_factura: {
				    title: 'Nº Documento',
				    width: '20%',
				    create: true,
				    edit: true,
				    list: true

			    },
			    PDF: {
                    title: '',
                    width: '2%',
                    sorting: false,
                    edit: false,
                    create: false,
                    display: function (datos) {
                    //Create an image that will be used to open child table
                    	var $img = $('<center><button title="Mostrar en PDF" class="btn btn-default btn-sm ui-corner-all" type="button" ><img src="../toolbar-icon/pdf.gif" title="" /></button></center>');
                        $img.on("click", function() {
												//alert(datos.record.id_ecompra);

                        window.open("../../reportes/compras.php?id_compra=" + datos.record.id_ecompra);
                                            //window.location = "../../reportes/cotizacion.php";

                       });
                       return $img;
                       }
                },    
			    
		    },
            formCreated: function (event, data) {
			    $("div[aria-describedby='ui-id-5']").css("top","0px");
			    $("div[aria-describedby='ui-id-3']").css("top","0px");
			    			
			    $("#ui-id-5, #ui-id-3").css('height','350px')
									  .css('top','0px')
									  .css('overflow-y','scroll')
									  .css('overflow-x','hidden');
			    $("#EditDialogSaveButton, #AddRecordDialogSaveButton").attr('class','btn btn-primary');
			    $("form input, form select").attr('class','form-control input-sm ui-corner-all');
            }

	    });
	    
		    
	    //Load person list from server
	    $('#jt_cheques_cancelados').jtable('load');
	    
	    //buscar Registros
	    $('#btnBUSCAR').on('click',function() {
		    $('#jt_cheques_cancelados').jtable('load', {
			 inicio:$('#inicio').val(),
             fin:$("#fin").val()
		    });
	    	// console.log($('#inicio').val());
	    	// console.log($('#fin').val());
		    defaultPrevented();
		    
	    });
	    //eliminar
	     // $('#DeleteAllButton').click(function () {
	    //         var $selectedRows = $('#PeopleTableContainer').jtable('selectedRows');
	    //         $('#PeopleTableContainer').jtable('deleteRows', $selectedRows);
	    //     });
    
		$("#inicio").datepicker({dateFormat: 'dd-mm-yy'});
		$("#fin").datepicker({dateFormat: 'dd-mm-yy'});	


    //Initialize validation logic when a form is created

	  $( "#dialog" ).dialog({
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
	      
			  $( this ).dialog( "close" );
		      },
		      "Eliminar": function() {
			  var $selectedRows = $('#jt_cheques_cancelados').jtable('selectedRows');
			  $('#jt_cheques_cancelados').jtable('deleteRows', $selectedRows);
			  $( this ).dialog( "close" );
		      }
		  }
	  });
      



			   
    });
        
        
      
</script>

<div id="dialog" title="Basic dialog">
  <p>Desea eliminar</p>
</div>

</body>
</html>