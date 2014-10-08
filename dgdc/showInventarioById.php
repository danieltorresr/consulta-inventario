<?php include("app.php");?>
<script type="text/javascript">
	$('.tab-pane').hide();
	$(".tabRubros").hide();
	$('#construccionesLimpiar').hide();
	$('.screenshot-opt').hide();
	$('.settings-opt').hide();
	var _get = {},
		rubro = null,
		parts = window.location.search.substr(1).split("&");
	for (var i = 0; i < parts.length; i++) {
	    var temp = parts[i].split("=");
	    _get[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
	}

	

	var editConstruncciones = {
		init : function(response){
			
			//put screenshot
			var screenshot = $('#screenshot-viewer');
				screenshot.attr('src', response.screenshot);

			//viewer
			var viewer = $('#showConstruccionesViewer');

			var numMarkers = currentRubro.markers.length,
				coordenadas = response.coordenadas;
			
			//set positions			
			$.each(coordenadas, function(index, coo) {				
				currentRubro.markerProps[currentRubro.markers.length].position = new google.maps.LatLng(coo[1],coo[0]);	
				prop = currentRubro.markerProps[currentRubro.markers.length];
				marker = currentRubro.setMarker(prop);
				currentRubro.markers.push(marker);
				//currentRubro.cantMarkers++;
			    //marker.init(currentRubro); 
			});

			//set lien to first and second marker
			currentRubro.linea = currentRubro.setLine('orange'); 


			//set values to calculate cadenamientos

			//to fixed values to 4 values 
			response.cadCarretera.inicial = (response.cadCarretera.inicial).toFixed(4);
			response.cadCarretera.final = (response.cadCarretera.final).toFixed(4);
			//

			viewer.find('#cadInicial').text(response.cadCarretera.inicial); 
			viewer.find('#cadFinal').text(response.cadCarretera.final); 
			

			
			//set markers to static map 
			mapa.markers = currentRubro.markers;


			/* ********************************************************* */

			    

			//set cadenamiento
			if(response.cadenamiento != undefined){
				imagesNavigation.estacion.cad = response.cadenamiento;				  	
				imagesNavigation.search();	
				imagesNavigation.cadToSearch.val(response.cadenamiento);  	
			}			

			//get values of server 
			console.log("get values", response);	

			//set medicion de la construccion
			viewer.find('#medidaConstruccion').text(response.medidaConstruccion);	

			//set distancia media 
			viewer.find('#distanciaMedia').text(response.distanciaMedia);		


      		/********************************** ***********************************************/

			//set ubicacion lado
			viewer.find('#ubicacion').text(response.ubicacionLado);


			//set ubicacion con respecto a la carretera
			viewer.find('#posicionNivel').text(response.posicionNivel);


			
			// set construcciones o grupo de construcciones			
			if(response.grupo){
				viewer.find('#construccionGrupo').text('grupo de construcción');
			}else{
				viewer.find('#construccionGrupo').text('construcción');
			}
			

			//set tipo de construccion
			viewer.find('#tipoConstruccion').text(response.tipo.nombre);

			// set niveles
			viewer.find('#niveles').text(response.niveles);

			//set curvatura
			viewer.find('#curvatura').text(response.curvatura.descripcion);

			//set acotamiento
			viewer.find('#acotamiento').text(response.acotamiento.descripcion);

			//set dispositivos
			if(response.dispositivos != undefined){
				viewer.find('#dispositivos').text(response.dispositivos.descripcion);
			}

			//set bandas de alerta 
			if(response.bandasDeAlerta != undefined){
				if(response.bandasDeAlerta){
					viewer.find('#bandasAlerta').text('si');	
				}else{
					viewer.find('#bandasAlerta').text('no');
				}	
				
			}
			

			//set elementos no abatibles
			var tagNoAbatibles = viewer.find('#elemNoAbt');
			if(response.noAbatibles){
				$.each(response.noAbatibles, function(index, el) {	
					var tagLabel = $('<div>');
						tagLabel.addClass('col-md-8');	
						tagLabel.addClass('body');
						tagLabel.text(el.nombre);

					var tagValue = $('<div>');
						tagValue.addClass('col-md-4');	
						tagValue.addClass('body');
						if(el.cantidad != undefined){
							tagValue.text(el.cantidad);	
						}else{
							tagValue.text(el.porcentaje + '%');	
						}

					tagNoAbatibles.after(tagValue);	
					tagNoAbatibles.after(tagLabel);
					
					
				});
			}		

			//set id to update record
			currentRubro.data.id = response._id.$id;
		}
	},

	editIntersecciones = {
		init : function(response){
			console.log("response",response);
			$('#clearFormInt').hide();

			//put screenshot
			var screenshot = $('#screenshot-viewer');
				screenshot.attr('src', response.screenshot);

			currentRubro.interseccionProp.position = new google.maps.LatLng(response.coordenadas[1],response.coordenadas[0]);

			//set marker 
			var marker = currentRubro.setMarker(currentRubro.interseccionProp);
				//marker.init(currentRubro);
				currentRubro.markers.push(marker);

			//set data to marker
			currentRubro.data.cadCarretera = response.cadCarretera;
			currentRubro.data.coordenadas = response.coordenadas;

			currentRubro.tagCadKm.val(currentRubro.data.cadCarretera);
			currentRubro.tagCadKmText.text(currentRubro.data.coordenadas);					

			//set markers to static map 
			mapa.markers = currentRubro.markers; 

			//set cadenamiento 
			
			if(response.cadenamiento != undefined){
				imagesNavigation.estacion.cad = response.cadenamiento;				  	
				imagesNavigation.search();	
				imagesNavigation.cadToSearch.val(response.cadenamiento);  	
			}				
			


			//viewer
			var viewer = $('#interseccionesForm');

			//set cad carretera 
			viewer.find('#cadenamiento').text(response.cadCarretera);

			//set tipo de interseccion			
			viewer.find('#tipoInterseccion').text(response.tipo);

			//set tipo de solucion			
			viewer.find('#tipoSolucion').text(response.tipoSolucion.descripcion);			

			//set poblacion de destino a la izquierda			
			viewer.find('#poblacionIzquierda').text(response.destinos[0].poblacion);	

			//set poblacion de destino a la derecha			
			viewer.find('#poblacionDerecha').text(response.destinos[1].poblacion);
			
		}
	},

	editAccesos = {
		init : function(response){
			console.log("response",response);
			$('#clearFormAccess').hide();

			//put screenshot
			var screenshot = $('#screenshot-viewer');
				screenshot.attr('src', response.screenshot);

			//set position
			currentRubro.interseccionProp.position = new google.maps.LatLng(response.coordenadas[1],response.coordenadas[0]);
			var marker = currentRubro.setMarker(interseccionProp);
				//marker.init(currentRubro);
			currentRubro.markers.push(marker);

			var viewer = $('#accesosForm');

			//set data to marker
			viewer.find('#cadenamiento').text(response.cadCarretera); 

			//set markers to static map 
			//mapa.markers = currentRubro.markers; 

			//set cadenamiento 
			
			if(response.cadenamiento != undefined){
				imagesNavigation.estacion.cad = response.cadenamiento;				  	
				imagesNavigation.search();	
				imagesNavigation.cadToSearch.val(response.cadenamiento);  	
			}				
						

			//set tipo
			viewer.find('#tipoInterseccion').text(response.tipo); 


			//set tipo de acceso
			if(response.tipoAcceso != undefined){
				viewer.find('#tipoAcceso').text(response.tipoAcceso); 
			}
		
			//set poblacion	

			//set poblacion de destino a la izquierda
			viewer.find('#poblacionIzquierda').text(response.destinos[0].poblacion); 	

			//set poblacion de destino a la derecha
			viewer.find('#poblacionDerecha').text(response.destinos[1].poblacion);

			//set propiedades
			if (response.propiedades !=undefined){
				viewer.find('#propiedades').text(response.propiedades.descripcion); 
			}

			//set distacia del derecho de via visible
			if(response.viaVisible !=undefined){
				viewer.find('#viaVisible').text(response.viaVisible); 
			}
			
		}
	},

	editCortesTerraplenes = {
		init : function(response){
			console.log("response",response);
			$('#cortesTerraplenesLimpiar').hide();

			//put screenshot
			var screenshot = $('#screenshot-viewer');
				screenshot.attr('src', response.screenshot);

			//set positions
			$.each(response.coordenadas, function(index, coo) {				
				
				var props = currentRubro.cortesTerrapletesProp[currentRubro.markers.length];
				props.position = new google.maps.LatLng(coo[1],coo[0]);
				var marker = currentRubro.setMarker(props);
					//marker.init(currentRubro);
				currentRubro.markers.push(marker);
			});

			//set linea
			var color = (response.rubro == 'terraplen')? 'blue' : 'orange'; 
				currentRubro.linea = currentRubro.setLine(color);

			//set data to markers
			//inicial
			//response.cadCarretera.inicial = (response.cadCarretera.inicial).toFixed(4);
			//
			currentRubro.data.cadCarretera.inicial= response.cadCarretera.inicial;
			currentRubro.data.coordenadas.inicial= response.coordenadas.inicial;
			var coo = currentRubro.data.coordenadas.inicial;
			currentRubro.tagCadInicial.val(currentRubro.data.cadCarretera.inicial);
			currentRubro.tagCadInicialText.text('('+coo[1]+',\n'+coo[0]+')');	

			//final
			//response.cadCarretera.final = (response.cadCarretera.final).toFixed(4);
			//
			currentRubro.data.cadCarretera.final = response.cadCarretera.final;
			currentRubro.data.coordenadas.final = response.coordenadas.final;	
			coo = currentRubro.data.coordenadas.final;
			currentRubro.tagCadFinal.val(currentRubro.data.cadCarretera.final);
			currentRubro.tagCadFinalText.text('('+coo[1]+',\n'+coo[0]+')');


			//calcular la medida
			//var medidaCT = 	(response.cadCarretera.final - response.cadCarretera.inicial);
			//console.log('medidaCT',medidaCT);

			//set medida
			currentRubro.tagMedida.val(response.medida);
			currentRubro.data.medida = response.medida;

			var viewer = $('#cortesTerraplenesForm');


			//set cad inicial
			viewer.find('#cadenamientoIni').text(response.cadCarretera.inicial);

			//set cad final
			viewer.find('#cadenamientoFin').text(response.cadCarretera.final);

			//set medida del corte o terraplen
			viewer.find('#medida').text(response.medida);

			////////////////////////////////////////////////////////////////////////

			//set markers to static map 
			mapa.markers = currentRubro.markers; 

			//set cadenamiento 
			
			if(response.cadenamiento != undefined){
				imagesNavigation.estacion.cad = response.cadenamiento;				  	
				imagesNavigation.search();	
				imagesNavigation.cadToSearch.val(response.cadenamiento);  	
			}				
			

			//set tipo terraplen / corte
			viewer.find('#tipo').text(response.rubro);		

			//set posicion			
			currentRubro.data.ubicacionLado = response.ubicacionLado;
			viewer.find('#posicion').text(response.ubicacionLado);			
		}
	};



	var edit = null;

	switch(_get.rubro) {
	    case "construcciones":
	        $("#construcciones").show();
	        rubro = rubros.tabConstrucciones;
	        edit = editConstruncciones;
	        break;
	    case "intersecciones":
	        $("#intersecciones").show();
	        rubro = rubros.tabIntersecciones;
	        edit = editIntersecciones;
	        break;
	    case "accesosirregulares":
	       $("#accesos").show();
	       rubro = rubros.tabAccesos;
	       edit = editAccesos;
	        break;
	    case "terraplen":
	       	$("#cortesTerraplenes").show();
	       	rubro = rubros.tabCortesTerraplenes;
	       	edit = editCortesTerraplenes;
	        break;   
	    case "corte":
	       	$("#cortesTerraplenes").show();
	       	rubro = rubros.tabCortesTerraplenes;
	       	edit = editCortesTerraplenes;
	        break;      
	    default:
	        
	        break;
	}

	currentRubro = Object.create(rubro);
	//currentRubro.init();

	setTimeout(function(){
		$.getJSON('includes/getInventarioById.php', _get, function(response, textStatus) {			
			edit.init(response);
		});							
	},2000);

</script>