<html>
<head>
	<title></title>
	<link href="img/semic_ico.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" href="library/bootstrap.min.css">
	<link rel="stylesheet" href="library/jquery.dataTables.css">
	<link rel="stylesheet" href="css/dgdc.css">
	<style type="text/css" media="screen">
		html, body, .container, .row{height: 100%; font-size: 12px;}
		
		.glyphicon-info-sign{color: #428bca; font-size: 16px;}
		caption{font-size: 20px; text-align: left;}
		hr{margin-top: 0px; margin-bottom: 0px;}
		h5{margin: 0;}
		th,td{
			text-align: center;
		}
		.panel-heading{margin-bottom: 10px;}

		#screenshot-modal .modal-dialog{
			width: 70%;
		}
		#screenshot-modal img{
			width: 100%;
		}

	</style>
</head>
<body>
	<!-- Modal -->
	<div class="modal fade" id="screenshot-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title" id="myModalLabel">Screenshot</h4>
	      </div>
	      <div class="modal-body">
	        <img id="screen-viewer" src="">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>	        
	      </div>
	    </div>
	  </div>
	</div>

	<div class="container">
		<div class="header text-center">
			<div class="info">
				<h4 class="carretera_label"></h4>
				<span class="sentido_label"></span><span class="carril_label"></span>
				<br>
				<span class="tramo_label"></span>	
			</div>
			<img class="imgLogo" src="img/dgdc.jpg">			
			<img class="imgSCTLogo" src="img/SCT_logo.png">
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading"><h5><i class="glyphicon glyphicon-filter"></i>&nbsp;Filtros</h5></div>
		  <div class="panel-body">
		    <form class="form-inline" role="form">			  

			  <div class="form-group filter_field">			    
			    <select class="form-control filterByRubro">
				  <option disabled selected>Rubro</option>
				  <option value="construcciones">Construrcciones</option>
				  <option value="intersecciones">Intersecciones</option>
				  <option value="accesos">Accesos</option>
				  <option value="cortes">Cortes</option>
				  <option value="terraplenes">Terraplenes</option>
				</select>
			  </div>

			  <div class="form-group filter_field">
			    <div class="input-group">			      
			      <input class="form-control cadIni" type="text" placeholder="Cadenamiento Inicial">
			      <div class="input-group-addon">Km</div>
			    </div>
			  </div>

			  <div class="form-group filter_field">
			    <div class="input-group">			      
			      <input class="form-control cadFin" type="text" placeholder="Cadenamiento Final">
			      <div class="input-group-addon">Km</div>
			    </div>
			  </div>			 

			</form>  
			 <button class="btn btn-default btn-sm clear-filter"><span class="glyphicon glyphicon-trash"></span> Limpiar filtros</button>
		  </div>
		</div>	
		<div class="body">
			<!-- <h2 style="text-align: center;">CARGA DE IMAGENES A BASE DE DATOS</h2> -->
			<div class="col-lg-12" style="padding: 0px 0px 5px 0px;" align="right">				
			</div>
			<div id="divTramos" class="col-lg-12" style="padding: 0px;">
				<div class="panel panel-default">
  					<div class="panel-heading"><h5><i class="glyphicon glyphicon-list"></i>&nbsp;Lista de inventario</h5></div>
						<table id="dataInventario" class="display" cellspacing="0">
							<thead>
								<tr>
									<th>Cadenamiento</th>
									<th>Rubro</th>										
									<th>Screenshot</th>								
									<th></th>
									<th></th>
								</tr>
							</thead>							
						</table>
					</div>
				</div>
			</div>
	</div>

	<script src="library/jquery.min.js" type="text/javascript"></script>
 	<script src="library/bootstrap.min.js" type="text/javascript"></script>
 	<script src="library/modal.js" type="text/javascript"></script>
 	
 	<script src="js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
	<script src="library/jquery.dataTables.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		var data = {},
			labels = {};

		var parts = window.location.search.substr(1).split("&");
		for (var i = 0; i < parts.length; i++) {
		    var temp = parts[i].split("=");
		    if(decodeURIComponent(temp[0])!='carretera'){
		    	data[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);	
		    }		    
		    labels[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
		}
			
		/*$.getJSON('includes/getInventarioByTramo.php', data, function(json, textStatus) {
				console.log("data json",json);
		});*/
		/* configuración datatable */
    $('#dataInventario').DataTable({        
	 "language": {
            "url": "js/Spanish.json"
        },        
    	"ajax": {
            "url": "includes/getInventarioByTramo.php",
            "data": data,
            "dataSrc": ""
        },
        "columns": [            
            { "data": "cadenamiento" },
            { "data": "rubro", "mRender" : function(data){
            		if(data == "accesos irregulares"){
            			return 'accesos';
            		}else{
            			return data;
            		}            		
            	}
            },
            
            { "data": null,
               "mRender" : function(){
            		return "";
            	},
				"fnCreatedCell" : function(td, sData){
					var btn = $("<button>");
						btn.html('<span class="glyphicon glyphicon-picture"></span>');
						btn.click(function(event) {
							var viewer = $('#screenshot-modal'),
								screenshot = $('#screen-viewer');								
							$.getJSON('includes/getScreenshotById.php', {id: sData._id.$id}, function(json, textStatus) {								
								screenshot.attr('src', json.screenshot);
								viewer.modal('show');					
							});							
						});
					btn.appendTo(td);						
				} 	
            },              
            { "data": null,  
            	"mRender" : function(){
            		return "";
            	},
				"fnCreatedCell" : function(td, sData, all){					
					var btn = $("<button>");
						btn.html('<span class="glyphicon glyphicon-eye-open"></span>');
						btn.click(function(event) {
							var url = "showInventarioById.php?"+"tramo="+data.tramo+"&sentido="+data.sentido+"&carril="+data.carril+"&rubro="+all.rubro.replace(" ", "")+"&id="+all._id.$id;
							//window.location = url;
							window.open(url,'_blank');
						});
					btn.appendTo(td);									

				} 
        	},
        	{ "data": null,  
            	"mRender" : function(){
            		return "";
            	},
				"fnCreatedCell" : function(td, sData, all){			

					if(all.rubro == 'construcciones'){
						//reoporte 
						var btn = $("<button>");
							btn.html('<span class="glyphicon glyphicon-file"></span>');
							btn.click(function(event) {
								var url = "reportes/reporte.php?"+"tramo="+data.tramo+"&sentido="+data.sentido+"&carril="+data.carril+"&id="+all._id.$id;
								//window.location = url;
								window.open(url,'_blank');
							});
						btn.appendTo(td);	
					}else{
						var btn = $("<button>");
							btn.html('<span class="glyphicon glyphicon-file"></span>');
							btn.click(function(event) {
								var url = "reportes/reportea.php?"+"tramo="+data.tramo+"&sentido="+data.sentido+"&carril="+data.carril+"&id="+all._id.$id;
								//window.location = url;
								window.open(url,'_blank');
							});
						btn.appendTo(td);	
					}				

				} 
        	}
        ]        
    });
		
		//put labels info
		$('.carretera_label').text(labels['carretera']);
		$('.sentido_label').text('S'+labels['sentido']);
		$('.carril_label').text('C'+labels['carril']);
		$('.tramo_label').text(labels['tramo']);

		//clear filters

	  var dataFilter = {
	  		table : $('#dataInventario').DataTable(), 
	  		oTable : $('#dataInventario').dataTable(), 
	  		rubro : $('.filterByRubro'),
	  		cadIni : $('.cadIni'),
	  		cadFin : $('.cadFin'),
	  		clear : $('.clear-filter'),
	  		init : function(){
	  			this.events();
	  			this.range();
	  		},
	  		events : function(){
	  			var _this = this;
	  			this.rubro.change(function(event) {
	  				_this.filter($(this).val(),1);
	  			});

	  			this.cadIni.keyup( function() {
			        _this.table.draw();
			    });

			    this.cadFin.keyup( function() {
			        _this.table.draw();
			    });

			    this.clear.click(function(event) {
			    	_this.clearFilter();
			    });
	  		},
	  		filter : function(val,index){	  			
	  			this.oTable.fnFilter(val);
	  		},
	  		clearFilter : function(){
	  			this.oTable.fnFilter('',7);
				this.oTable.fnFilter('');
				this.cadIni.val('');
				this.cadFin.val('');
				this.table.draw();
	  		},
	  		range : function(){
	  			var _this = this;
	  			$.fn.dataTable.ext.search.push(
				    function( settings, data, dataIndex ) {
				        var min = parseInt( _this.cadIni.val(), 10 ),
				        	max = parseInt( _this.cadFin.val(), 10 ),
				        	cadenamiento = parseFloat( data[0] ) || 0; // use data for the cadenamiento column
				 
				        if ( ( isNaN( min ) && isNaN( max ) ) ||
				             ( isNaN( min ) && cadenamiento <= max ) ||
				             ( min <= cadenamiento   && isNaN( max ) ) ||
				             ( min <= cadenamiento   && cadenamiento <= max ) )
				        {
				            return true;
				        }
				        return false;
				    }
				);	
	  		}
	  };

	  dataFilter.init();

	  //search by range
	  

	</script>

</body>
</html>