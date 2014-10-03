<!DOCTYPE html>
<html>
<head>
	<title>Semic | Cargador</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="img/semic_ico.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" href="library/bootstrap.min.css">
	<link rel="stylesheet" href="library/jquery.dataTables.css">
	<link rel="stylesheet" href="css/dgdc.css">
	<style type="text/css" media="screen">
		html, body, .container, .row{height: 100%; font-size: 12px;}
		#divTramos, #dataTramos{height: 95%;}
		.glyphicon-info-sign{color: #428bca; font-size: 16px;}
		caption{font-size: 20px; text-align: left;}
		hr{margin-top: 0px; margin-bottom: 0px;}
		h5{margin: 0;}
		.panel-heading{margin-bottom: 10px;}
	</style>
</head>
<body>
	<div class="container">	
		<div class="header text-center">
			<img class="imgLogo" src="img/dgdc.jpg">
			<span class="title">Inventario Virtual</span>
			<img class="imgSCTLogo" src="img/SCT_logo.png">
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading"><h5><i class="glyphicon glyphicon-wrench"></i>&nbsp;Filtros</h5></div>
		  <div class="panel-body">
		    <form class="form-inline" role="form">
			  
			  <div class="form-group filter_field">			    
			    <select class="form-control">
				  <option disabled selected>Estados</option>
				  <option>2</option>
				  <option>3</option>
				  <option>4</option>
				  <option>5</option>
				</select>
			  </div>

			  <div class="form-group filter_field">			    
			    <select class="form-control">
				  <option disabled selected>Aprovechamiento</option>
				  <option>2</option>
				  <option>3</option>
				  <option>4</option>
				  <option>5</option>
				</select>
			  </div>

			  <div class="form-group filter_field">
			    <div class="input-group">			      
			      <input class="form-control" type="email" placeholder="Cadenamiento Inicial">
			      <div class="input-group-addon">Km</div>
			    </div>
			  </div>

			  <div class="form-group filter_field">
			    <div class="input-group">			      
			      <input class="form-control" type="email" placeholder="Cadenamiento Final">
			      <div class="input-group-addon">Km</div>
			    </div>
			  </div>

			</form>  
		  </div>
		</div>
		<div class="body">
			<!-- <h2 style="text-align: center;">CARGA DE IMAGENES A BASE DE DATOS</h2> -->
			<div class="col-lg-12" style="padding: 0px 0px 5px 0px;" align="right">
				
			</div>
			<div id="divTramos" class="col-lg-12" style="padding: 0px;">
				<div class="panel panel-default">
  					<div class="panel-heading"><h5><i class="glyphicon glyphicon-list"></i>&nbsp;Lista de estaciones cargadas actualmente</h5></div>
						<table id="dataTramos" class="display" cellspacing="0">
							<thead>
								<tr>
									<th>Carretera</th>
									<th>Tramo</th>								
									<th>Sentido</th>
									<th>Carril</th>
									<th>Estado</th>
									<th>Eje Carretero</th>
									<th>Concesionario</th>
									<th></th>
								</tr>
							</thead>							
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="cargadorModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title">Cargar archivo</h4>
	      </div>
	      <div id="modalBody" class="modal-body">
	        <p>One fine body&hellip;</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

 	<script src="library/jquery.min.js" type="text/javascript"></script>
 	<script src="library/bootstrap.min.js" type="text/javascript"></script>
 	<script src="library/modal.js" type="text/javascript"></script>
	<script src="library/jquery.dataTables.js" type="text/javascript"></script>

 <script type="text/javascript">
 /* configuración datatable */
    $('#dataTramos').DataTable({        
	 "language": {
            "url": "js/Spanish.json"
        },
    	"ajax": {
            "url": "includes/getGroupCarreteras.php",
            "dataSrc": ""
        },
        "columns": [            
            { "data": "carretera" },
            { "data": "tramo" },
            { "data": "sentido" },
            { "data": "carril" },
            { "data": "estado" },
            { "data": "ejeCarretero" },
            { "data": "concesionario" },
            { "data": null,  
            	"mRender" : function(){
            		return "";
            	},
				"fnCreatedCell" : function(td, sData){
					/*var btn = $("<button>");
						btn.text('Capturar');
						btn.click(function(event) {
							var url = "inventario.php?"+"tramo="+sData.tramo+"&sentido="+sData.sentido+"&carril="+sData.carril;
							//window.location = url;
							window.open(url,'_blank');

						});
					btn.appendTo(td);*/
					console.log(sData);
					var btn = $("<button>");
						btn.text('Inventario');
						btn.click(function(event) {
							var url = "inventario.php?"+"tramo="+sData.tramo+"&sentido="+sData.sentido+"&carril="+sData.carril+'&carretera='+sData.carretera;
							//window.location = url;
							window.open(url,'_blank');

						});
					btn.appendTo(td);	
				} 
        	},

        ]        
    });
	
 
 /* modal para carga */
 $( "body" ).on( "click", "#btnCarrilModal",function(){
 		$('#cargadorModal').modal("toggle");
 		//var claveTramo = $(this).attr("data-id");
 		$('#modalBody').load('cargador/index.php');
 	});

 </script>
</body>
</html>