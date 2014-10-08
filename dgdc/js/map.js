	$('#map-canvas').css('height', '90%');

	var mapOptions = {
    zoom: 18,
    draggableCursor: 'crosshair',
    center: new google.maps.LatLng(0, 0),
    mapTypeId: google.maps.MapTypeId.HYBRID
	};
	var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
	
	/*calculate .... */
	var rad = function(x) {return x*Math.PI/180;}
	var distHaversine = function(p1, p2) {  
		var R = 6371; // earth's mean radius in km
		var dLat  = rad(p2.lat() - p1.lat());
		var dLong = rad(p2.lng() - p1.lng());

	  	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
	          Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) * Math.sin(dLong/2) * Math.sin(dLong/2);
	  	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	  	var d = R * c;

	  return (d*1000).toFixed(3);	 
	}
	
	mapa.useMap(map);

	var _estacion = imagesNavigation;				
		_estacion.map = map;						
		//_estacion.first();	

	var navigationOnPolyLine = {
		load : function(event){
			var data = _parentEstacion;
			data.lng = event.latLng.lng();
			data.lat = event.latLng.lat();
			$.getJSON('includes/cadMasCercano.php', data, function(json, textStatus) {		
					
					var cad;
					for (var est in json) {
						cad = json[est].cadGeometrico;
	   					 break;
					}	

				_estacion.estacion.cad = cad;
				_estacion.load();									 

			});
		}
	}
 	 
	$.getJSON('includes/listCarreteras.php', _parentEstacion, function(json, textStatus) {			
			var path=[]; 
			$.each(json, function(index, val) {
				 path.push(new google.maps.LatLng(val.latitud, val.longitud));
			     //setMarcador(new google.maps.LatLng(val.latitud, val.longitud), val.cadGeometrico.toString())
			});	

			//init process to images
			_parentEstacion.cad = json[0].cadGeometrico;
			imagesNavigation.init();

			map.panTo(path[0]);
			var lineaEstaciones = new google.maps.Polyline({
    				path: path,
    				strokeColor:'#90BEE4' ,
    				strokeOpacity: 1,
    				strokeWeight: 8
  					});
			var lineaEstacionesfront = new google.maps.Polyline({
    				path: path,
    				strokeColor:'#428CCA' ,
    				strokeOpacity: 1,
    				strokeWeight: 2
  					});

			lineaEstaciones.setMap(map);
			lineaEstacionesfront.setMap(map);
					

			google.maps.event.addListener(lineaEstaciones, 'click', function(event) {
			    //console.log("lat:",event.latLng.lat(),"lng:",event.latLng.lng());
			  	navigationOnPolyLine.load(event);
			   	 	
			});		

			google.maps.event.addListener(lineaEstacionesfront, 'click', function(event) {
			    //console.log("lat:",event.latLng.lat(),"lng:",event.latLng.lng());
			  	navigationOnPolyLine.load(event);
			   	 	
			});						

		});

	/* change icons */
	var imagePosicion = {
		    url: 'img/posicion.png',
		    // This marker is 20 pixels wide by 32 pixels tall.
		    size: new google.maps.Size(20, 20),
		    // The origin for this image is 0,0.
		    origin: new google.maps.Point(0,0),
		    // The anchor for this image is the base of the flagpole at 0,32.
		    anchor: new google.maps.Point(10, 10)
		};

	var imageInicial = {
		    url: 'img/ini.png',
		    // This marker is 20 pixels wide by 32 pixels tall.
		    size: new google.maps.Size(20, 20),
		    // The origin for this image is 0,0.
		    origin: new google.maps.Point(0,0),
		    // The anchor for this image is the base of the flagpole at 0,32.
		    anchor: new google.maps.Point(10, 10)
		};
	
	var imageFinal = {
		    url: 'img/fin.png',
		    // This marker is 20 pixels wide by 32 pixels tall.
		    size: new google.maps.Size(20, 20),
		    // The origin for this image is 0,0.
		    origin: new google.maps.Point(0,0),
		    // The anchor for this image is the base of the flagpole at 0,32.
		    anchor: new google.maps.Point(10, 10)
		};	

		var imageDistancia = {
		    url: 'img/distan.png',
		    // This marker is 20 pixels wide by 32 pixels tall.
		    size: new google.maps.Size(20, 20),
		    // The origin for this image is 0,0.
		    origin: new google.maps.Point(0,0),
		    // The anchor for this image is the base of the flagpole at 0,32.
		    anchor: new google.maps.Point(10, 10)
		};	