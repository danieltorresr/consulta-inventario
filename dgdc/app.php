<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inventarios Digitales 1.0</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="img/semic_ico.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" href="library/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
		<div id="contenedor" class="row">					
			<div id="fotos-panoramicas" class="col-lg-12">	
				<!-- Screenshoot options-->
				<div class="screenshot-opt">					
					<div class="dropdown">
						  <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
						    <span class="glyphicon glyphicon-camera"></span>
						    <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
						    <li role="presentation" id="generate-static-map"><a role="menuitem" tabindex="-1" href="#">Generar Screenshot</a></li>						    
						  </ul>
					</div>
				</div>	
				<!-- end -->
				<!-- Cadenamiento opt-->
				<div class="cad-opt">
					<div class="form-group">
					    <div class="input-group">						      					      
					      <input class="form-control" type="text" name="searchText" placeholder="Cadenamiento">					      
					      <div class="input-group-addon search-cad-img">
					      	<span class="glyphicon glyphicon-search"></span> 
					      </div>					      
						</div>
					</div>					
				</div>
				<!-- end-->
				<!-- settings Screenshoot options-->
				<div class="settings-opt">					
					<div class="dropdown">
						  <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown">
						    <span class="glyphicon glyphicon-cog"></span>
						    <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
						    <li role="presentation" id="fastNavigaton">						    	
								<a><label><input type="checkbox"><span class="glyphicon glyphicon-flash"></span> Navegacion rapida</label></a> 					    
						    </li>
						    <li role="presentation" id="saveUbication">						    	
								<a><span class="glyphicon glyphicon-floppy-saved"></span> Guardar mi ubicacion</a>
						    </li>			
						    <li role="presentation" id="getSaveubication">						    	
								<a> <span class="glyphicon glyphicon-map-marker"></span> Ultima ubicacion</a>
						    </li>			    
						  </ul>
					</div>
				</div>
				<!-- end -->
				<!-- images -->
				<div class="images-panel">
					<div class="images-body">
						<img id="imgizq" class="col-lg-4" src="">
						<img id="imgcen" class="col-lg-4" src="">   
						<img id="imgder" class="col-lg-4" src=""> 
					</div>					  
				</div>				   
				<!-- end -->
				<!-- images navigator -->
					<div class="images-navigator">
						<button class="btn btn-sm img-opt">							
							<span class="glyphicon glyphicon-picture"></span>							    
						</button>						
						<div class="btn-group  navigator-img-opts">
						  <button id="primerImagen" type="button" class="btn btn-default"><span class="glyphicon glyphicon-step-backward"></span></button>
						  <button id="anteriorImagen" type="button" class="btn btn-default"><span class="glyphicon glyphicon-backward"></span></button>
						  <button id="siguienteImagen" type="button" class="btn btn-default"><span class="glyphicon glyphicon-forward"></span></button>
						  <button id="ultimaImagen" type="button" class="btn btn-default"><span class="glyphicon glyphicon-step-forward"></span></button>
						</div>  
					</div>	
				<!-- end -->
			</div>
			<div id="map-canvas" class="col-lg-6"></div>
			<div id="tools"class="col-lg-6">
				<div class="row">
					<!--div id="tools-container" class="col-lg-12"></div-->
					<div id="tools-rubros" class="col-lg-12"></div>
					<div class="col-lg-12">
						<ul id="myTab" class="nav nav-tabs" role="tablist">
						  <li id="tabConstrucciones" class="active tabRubros"><a href="#construcciones" role="tab" data-toggle="tab">Construcciones</a></li>
						  <li id="tabIntersecciones" class="tabRubros" ><a href="#intersecciones" role="tab" data-toggle="tab">Intersecciones</a></li>
						  <li id="tabAccesos" class="tabRubros"><a href="#accesos" role="tab" data-toggle="tab">Accesos</a></li>
						  <li id="tabCortesTerraplenes" class="tabRubros"><a href="#cortesTerraplenes" role="tab" data-toggle="tab">Cortes y Terraplenes</a></li>
						</ul>
						
						<!-- Tab panes -->
						<div class="tab-content">
						  <div class="tab-pane active" id="construcciones"><?php include("vistas/construcciones.html");?></div>
						  <div class="tab-pane" id="intersecciones"><?php include("vistas/intersecciones.html");?></div>
						  <div class="tab-pane" id="accesos"><?php include("vistas/accesos.html");?></div>
						  <div class="tab-pane" id="cortesTerraplenes"><?php include("vistas/cortesTerraplenes.html");?></div>
						</div>
					</div>					
				</div>
			</div>
		</div>

		<!-- Canvas to screenshot -->
		<canvas id="canvas-screenshot" width="1200" height="600" style="display:none;"></canvas>

		<!-- Static map to screenshot-->
		<img id="static-map"class="col-lg-12" style="display:none;">

		<!-- Modal to show screenshot -->
		<?php include("vistas/screenshot.html");?>

<script src="library/jquery.min.js" type="text/javascript"></script>
<script src="library/bootstrap.min.js" type="text/javascript"></script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAeTbCOpuPIKT4i9n8iUQsBHNUt_MWjtog&sensor=false"></script>

<script type="text/javascript">		
		var parts = window.location.search.substr(1).split("&"),
			_parentEstacion = {};

		for (var i = 0; i < parts.length; i++) {
		    var temp = parts[i].split("=");
		    if(temp[0] == "tramo" || temp[0] == "sentido" || temp[0] == "carril"){
		    	_parentEstacion[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);	
		    }		    
		}		
	console.log("estacion actual de trabajo:",_parentEstacion);	
</script>

<script type="text/javascript" src="js/catalogoTipoConstruccion.js"></script>
<script type="text/javascript" src="js/screenshot.js"></script>
<script type="text/javascript" src="js/imagesControl.js"></script>
<script type="text/javascript" src="js/map.js"></script>



<script type="text/javascript" src="js/app.js"></script>


<script type="text/javascript">
	//Activar tab
	  //$('#myTab').tab('show'); <-- No es necesario

 $('#btnn').click(function(event) {
 		alert();
 });

	/* HERRAMIENTAS DE RUBROS */
	var createButton = function(btn){
		btn.element = $("<button>");
		btn.element.html(btn.text);
	    this.boton = btn.element;
	    this.container = btn.container;
	    $(btn.container).append(btn.element);
	};	
    var contruccioneOpciones;
	var construcciones = 
	{	
		container: undefined,
        boton: undefined,
        opcionesContainer : $('#tools-rubros'),       
		crear:createButton,
		alerta: function(){
			alert(this.boton.attr('id'));
		},
		setClick: function(funcion){
			this.boton.click(funcion);
		},
		setOptions: contruccioneOpciones,
		unsetOption:function(){
			$('#tools-rubros').html();	
		}

	}



	construcciones.crear({text:"Construcciones", container:"#tools-container"});
	construcciones.setClick(function(){
		construcciones.opcionesContainer.html("hola");
	});

</script>
</body>
</html>
