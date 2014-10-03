
/*get datos guardados y ponerlos en el mapa*/

var _getMedidasAnteriores = {
		_allMarkersAnteriores : new Array(),
		_allLinesAnteriores : new Array(),		
		init : function(show,rubro){
			if(show){
				this.load(rubro);
			}else{
				this.clear();
			}
		},	
		load : function(rubro){					
			
			var data = {
				tramo : _parentEstacion.tramo,
				sentido : _parentEstacion.sentido,
				carril : _parentEstacion.carril,				
				rubro : rubro
			},_this = this;

			$.getJSON('includes/getMedicionesRubro.php', data, function(json, textStatus) {			 
					 for (var i in json) {
					 	var coordenadas = json[i].file.coordenadas;
					 		_this.rubro = json[i].file.rubro;				 		
					 		
					 		if(coordenadas.length == undefined){
					 			var temp = new Array();
					 			_this.putMultiple(coordenadas,temp);		
					 		}else{
					 			_this.putSingle(coordenadas);		
					 		}
					 				 	            
					 }
			});		
		},
		putSingle : function(coordenadas){
			var marker = this.setMarker(coordenadas[0] , coordenadas[1]);
				marker.setIcon(imageDistancia);
		},
		putMultiple : function(coordenadas,temp){
			var icons = [imageInicial,imageFinal,imageDistancia];
			for (var c in coordenadas) {
				var new_marker = this.setMarker(coordenadas[c][0] , coordenadas[c][1]);
				temp.push(new_marker);												
			}
			if(temp.length>=2){
				var color = (this.rubro == 'terraplen')? 'blue' : 'orange';				
				var linea = new google.maps.Polyline({
			   				path: [temp[0].getPosition(),temp[1].getPosition()],
			   				strokeColor:color ,
			   				strokeOpacity: 1,
			   				strokeWeight: 4
						});	
			 	linea.setMap(map);
			 	this._allLinesAnteriores.push(linea);

			 //set icons	
			 for (var i = 0; i < temp.length; i++) {
			 		temp[i].setIcon(icons[i]);
			 	};	

			}	
		},
		setMarker:function(lng,lat){
			var _this = this,
				new_marker = new google.maps.Marker({
						map: map,										 				
						position:{lng:lng , lat:lat}	
					});			
			this._allMarkersAnteriores.push(new_marker);

			return new_marker;
		},
		clear : function(){
			for (var i = 0; i < this._allMarkersAnteriores.length; i++) {
				this._allMarkersAnteriores[i].setMap(null);
			};	

			for (var i = 0; i < this._allLinesAnteriores.length; i++) {
				this._allLinesAnteriores[i].setMap(null);
			};
		}
};


/*function setMarcador(latLng,title,hide){
			return new google.maps.Marker({
      		position: latLng,
      		map: map,
      		title: title,
      		draggable:false,
      		visible:hide
        	}); 			
		}*/
var setMarker = function (props){
			return new google.maps.Marker(props); 			
		};
var setLine = function (color){
	var linea = new google.maps.Polyline({
    				path: [this.markers[0].getPosition(),this.markers[1].getPosition()],
    				strokeColor:color ,
    				strokeOpacity: 1,
    				strokeWeight: 4
  					});
		linea.setMap(this.map);
		return linea;		
		};		
var unsetMarkers = function (){
			for (var i = 0; i < this.markers.length; i++) {
				this.markers[i].setMap(null);
			};		
		};

var calcularTriangulo = function(pointA, pointB, pointC){
	var a,b,c,semi_perimetro,area,h,x;
	a =  distHaversine(pointA.getPosition(), pointB.getPosition())*1;
		
	b =  distHaversine(pointB.getPosition(),pointC.getPosition())*1;
				
	c =  distHaversine(pointC.getPosition(),pointA.getPosition())*1;

	console.log("pointB.getPosition()",pointB.getPosition());
	console.log(",pointC.getPosition()",pointC.getPosition());

			//calculate semi perimetro	
	semi_perimetro = (b + c + a)/2;
			//calculate area
	area = (semi_perimetro * (semi_perimetro-a) * (semi_perimetro-b) * (semi_perimetro-c));
	area = Math.sqrt(area);
			//calculate h
	h = (2 * area) / b;
			//calculate x 
	x = Math.sqrt((a * a) - (h * h));
	/*		console.log("a= "+a);
			console.log("b= "+b);
            console.log("c= "+c);
            console.log("h= "+h);
            console.log("semi= "+semi_perimetro);
            console.log("area ="+ area);
			console.log("x= "+x);
			console.log("cadenamiento= "+(pointB.cadCarretera+x));
			
	*/
	return {base:b,ladoa:a,ladoc:c, altura:h, x:x, newCadenamiento: (pointB.cadCarretera+x) };
};


var	cadMasCercano = function(marker,parent){
		var _setMarker, _map, _cantMarkers, _data, _showData,b, c;
		_setMarker 		   = parent.setMarker;
		_map       		   = parent.map;
		_calcularTriangulo = parent.calcularTriangulo;
		_markers 		   = parent.markers;		

		var data = _parentEstacion;
			data.lng = marker.getPosition().lng();
			data.lat = marker.getPosition().lat();

		$.getJSON('includes/cadMasCercano.php', data, function(json, textStatus) {				
			
			if (json.length == 0) {
				alert("Fuera de rango, intentelo de nuevo.");
				marker.setMap(null);
				_markers.splice((_markers.length-1), 1);				

				return false;
			}else{
				var cad = [];
				$.each(json, function(index, val) {
				    var props, marker;
				        props               = {map: _map,visible:false,position:{lng:val.longitud,lat:val.latitud}};
					    marker              = _setMarker(props);	
					    marker.cadCarretera =  val.cadCarretera;
					    cad.push( marker );					
				});
				
				//console.log("cad[0].cadCarretera",cad[0].cadCarretera);
				//console.log("cad[1].cadCarretera",cad[1].cadCarretera);

				b = (cad[0].cadCarretera < cad[1].cadCarretera)? cad[0] : cad[1]; 
				c = (cad[0].cadCarretera < cad[1].cadCarretera)? cad[1] : cad[0];  
				//console.log(b.cadCarretera + " < "+c.cadCarretera);

				marker.setTags(_calcularTriangulo(marker, b, c));
			}					
		});

	};

var construccionesMarkerProps = [
			{
      		map: this.map,
      		title:"primero",
      		name:"click1",
      		icon: imageInicial,
	      		init: function(parent){
	      			    this.parent = parent;
	      				cadMasCercano(this,parent);
	      			},
	      		setTags: function(triangulo){
	      			console.log(triangulo);

	      			triangulo.newCadenamiento = (triangulo.newCadenamiento).toFixed(4);

	      			this.parent.data.cadInicial = triangulo.newCadenamiento;
	      			this.parent.tagCadInicial.val(this.parent.data.cadInicial); 
	      			this.parent.tagCoordInicial.text(this.getPosition());
	      			this.parent.data.CoordInicial = [this.getPosition().lng(),this.getPosition().lat()];      			

	      		}
      		}
      		,
        	{
      		map: this.map,
      		title:"segundo",
      		name:"click2",
      		icon: imageFinal,
      		init: function(parent){
      			this.parent = parent;
      			cadMasCercano(this,parent);

      		},
      		setTags: function(triangulo){      			
      			     			
      			this.parent.linea = this.parent.setLine('orange'); 
      			triangulo.newCadenamiento = (triangulo.newCadenamiento).toFixed(4);      			

      			var inicial = this.parent.tagCadInicial.val(),
      				coordInicial = this.parent.data.CoordInicial;

      			if(inicial>triangulo.newCadenamiento){
      				
      				this.parent.tagCadInicial.val(triangulo.newCadenamiento);
      				this.parent.data.cadInicial = triangulo.newCadenamiento;
      				this.parent.data.CoordInicial = [this.getPosition().lng(),this.getPosition().lat()];
      				this.parent.tagCoordInicial.text(this.getPosition());



      				this.parent.tagCadFinal.val(inicial);
      				this.parent.data.cadFinal = inicial; 
      				this.parent.tagCoordFinal.text("("+coordInicial[1]+",\n"+coordInicial[0]+")");
      				this.parent.data.CoordFinal = coordInicial;
      				
      			}else{
      				this.parent.tagCadFinal.val(triangulo.newCadenamiento);
      				this.parent.data.cadFinal = triangulo.newCadenamiento; 
      				this.parent.tagCoordFinal.text(this.getPosition());
      				this.parent.data.CoordFinal = [this.getPosition().lng(),this.getPosition().lat()];
      			}

      			this.parent.data.cadInicial = (this.parent.data.cadInicial * 1).toFixed(4);
      			this.parent.data.cadFinal = (this.parent.data.cadFinal * 1).toFixed(4);

      			this.parent.tagCadInicial.val(this.parent.data.cadInicial);
      			this.parent.tagCadFinal.val(this.parent.data.cadFinal);      			

      		}
        	},
        	{
      		map: this.map,
      		title:"tercero",
      		name:"click3",
      		icon: imageDistancia,
      		draggable:true,
      		init: function(parent){
      			this.parent = parent;
      			this.setTags(this.parent.calcularTriangulo(this, this.parent.markers[0], this.parent.markers[1]));
      			this.addDraggEvent();
      		},
      		setTags: function(triangulo){
      			console.log(triangulo);

      			var medida = (this.parent.data.cadFinal - this.parent.data.cadInicial).toFixed(4);
      			this.parent.data.distanciaMedia = (triangulo.altura).toFixed(4);      			

      			this.parent.tagDistMedia.val(this.parent.data.distanciaMedia); 
      			this.parent.data.orillas = [this.getPosition().lng(),this.getPosition().lat()];
      			
      			this.parent.tagMedidaConstruccion.val(medida);
      			this.parent.data.medidaConstruccion = medida;
      			//this.parent.data.medidaConstruccion = medida;
      			//$('#mcrcarretera').text('Con respecto a la carretera: '+medida);

      			console.log("medida de la construccion",triangulo.base);
      			console.log("medida con respecto a la carretera",medida);
      		},
      		addDraggEvent: function(){
      				var _this = this;
      				google.maps.event.addListener(_this, 'dragend', function(event) {
      					console.log(_this);
					   _this.setTags(_this.parent.calcularTriangulo(_this, _this.parent.markers[0], _this.parent.markers[1]));
					});	
      			}      			
        	}
        	];

var opNoAbatiblesNumerico = function(data){
	for (var i = 0; i < 11; i++) {
				var tagOptions =$('<option>');
				 tagOptions.text(i);
				 tagOptions.val(i);
				$(this).append(tagOptions);	
			};
   $(this).change(function(event) {
				var tagLabel = $('<p>');
				tagLabel.addClass('noAbatibles-text');
				var cantidad = $(this).val();
				tagLabel.text(cantidad+" - "+$(this).data('nombre'));
				$(this).after(tagLabel);
				data.push( {id: $(this).data('id'),nombre:$(this).data('nombre'),cantidad:$(this).val()});

				$(this).remove();
			});
		
};

var opNoAbatiblesPorcentaje =  function(data){
	var tagOptions = $('<option>');
		tagOptions.text("0%");
		tagOptions.val("0%");
		$(this).append(tagOptions);
	for (var i = 10; i <= 100; i+=10) {
				 tagOptions = $('<option>');
				 tagOptions.text(i+"%");
				 tagOptions.val(i+"%");
				$(this).append(tagOptions);	
			};
   $(this).change(function(event) {
				var tagLabel = $('<p>');
				tagLabel.addClass('noAbatibles-text');
				var cantidad = $(this).val();
				tagLabel.text(cantidad+" - "+$(this).data('nombre'));
				$(this).after(tagLabel);
				
				data.push( {id: $(this).data('id'),nombre:$(this).data('nombre'),porcentaje:$(this).val().replace("%","")});

				$(this).remove();
			});
		
};



$.fn.opcioneNoAbatibles;
        	

var construccionesProto = {
	tagLimpiar : $("#construccionesLimpiar"),
	tagCadInicial : $("input[name=cadenamientoInicial]"),
	tagCadFinal : $("input[name=cadenamientoFinal]"),
	tagDistMedia : $("input[name=distanciaMedia]"),
	tagCoordInicial : $("#cadInicial-text"),
	tagCoordFinal : $("#cadFinal-text"),
	tagTipoConstruccion: $("#tipoConstruccion"),
	tagUbicacion: $('input[name="ubicacion"]'),
	tagPosicionNivel : $('[name="posicionNivel"]'),
	tagGrupoConstrucciones : $('[name="grupoConstrucciones"]'),
	tagMedidaConstruccion :  $('[name="medidaConstruccion"]'),
	tagElemNoAbatibles : $('[name="elemNoAbatibles"]'),
	tagNivelConstruccion: $('[name="nivelConstruccion"]'),
	tagCurvatura: $('[name="curvatura"]'),
	tagAcotamiento: $('[name="acotamiento"]'),
	tagDispositivos: $('[name=dispositivos]'),
	tagBandasDeAlerta: $('[name=bandasDeAlerta]'),
	data:{noAbatibles:[], screenshot:''},
	rubro:"construcciones",
	markers:[],
	cantMarkers:0,
	map: map,
	showData:function(){
		console.log(this.data);
		this.tagCadInicial;
	},
	markerProps: construccionesMarkerProps, 
	clickMap: function(event){
		var marker, prop;		
		if (this.markers.length == 3) {
			this.unsetMarkers();
			this.linea.setMap(null);
			this.markers = [];
			this.cantMarkers = 0;
			this.tagCadInicial.val(0);
		this.data.cadInicial=0;
		this.tagCadFinal.val(0);
		this.data.cadFinal=0;
		this.tagDistMedia.val(0);
		this.data.distanciaMedia=0;
		this.tagMedidaConstruccion.val(0);
		this.data.medidaConstruccion=0;
		this.tagCoordInicial.text("");
		this.data.CoordInicial = [];
		this.tagCoordFinal.text("");
		this.data.CoordFinal = [];
		};

		this.markerProps[this.markers.length].position = event.latLng;
		prop = this.markerProps[this.markers.length];
		marker = this.setMarker(prop);
		this.markers.push(marker);
		this.cantMarkers++;
	    marker.init(this);
		
	},
	setMarker : setMarker,
	setLine : setLine,	
	calcularTriangulo : calcularTriangulo,
	init : function(){
		//alert(this.rubro);
		this.markers = [];
			this.cantMarkers = 0;
		this.clearTags();
		this.addFormEvents();
	},
	addFormEvents:function(){
		var tagTipoConstruccion, data,_this;
		_this=this; 
		tagTipoConstruccion = this.tagTipoConstruccion;
		data = this.data;
		this.tagTipoConstruccion.change(function(event) {
			/* tipode construccion */
			data.tipo = tagTipoConstruccion.find("option:selected").data();			
		});
		this.tagUbicacion.change(function(event) {
			/* tipode ubicacion */

			data.ubicacionLado = $(this).val();
		});
		this.tagPosicionNivel.change(function(event) {
			/* Posicion Nivel */
			data.posicionNivel = $(this).val();
		});
		this.tagGrupoConstrucciones.change(function(event) {
			/* Grupo de construcciones */
			data.grupo = $(this).val();
		});
		this.tagElemNoAbatibles.prev("span").click(function(event) {
			/* Act on the event */
			data.noAbatibles={};
			$('.noAbatibles-text').remove();
		});
		this.tagCurvatura.change(function(event) {	
			/* Curvatura*/
			data.curvatura =$(this).find("option:selected").data();	
		});
		this.tagAcotamiento.change(function(event) {	
			/* Acotamiento*/ 
			data.acotamiento = $(this).find("option:selected").data();	
		});

		this.tagDispositivos.change(function(event) {
			/* Dispositivos*/
			data.dispositivos = $(this).find("option:selected").data();	
		});

		this.tagBandasDeAlerta.change(function(event) {
			/*tipode ubicacion*/
			data.bandasDeAlerta = $(this).val();
		});

		this.tagElemNoAbatibles.change(function(event) {
			var datos, tagSelect;
			$(this).next(".noAbatibles-select").remove();
			datos = $(this).find("option:selected").data();
			if(datos.nombre!=undefined){			
			tagSelect = $('<select>');
			tagSelect.data('nombre', datos.nombre);
			tagSelect.data('id', datos.id);
			tagSelect.addClass('noAbatibles-select');
			$.fn.opcioneNoAbatibles = (datos.opcion == "numerico")?opNoAbatiblesNumerico:opNoAbatiblesPorcentaje;
			 tagSelect.opcioneNoAbatibles(data.noAbatibles);
			
			$(this).after(tagSelect);
			}
		});
		this.tagNivelConstruccion.change(function(event) {
			/* Act on the event */
			data.niveles = $(this).val();
		});
		this.tagLimpiar.click(function(event) {
			event.preventDefault();
			console.log(data);	
			_this.clearTags();		
		});		
	},
	clearTags: function(){
		this.tagCadInicial.val(0);
		this.data.cadInicial=0;
		this.tagCadFinal.val(0);
		this.data.cadFinal=0;
		this.tagDistMedia.val(0);
		this.data.distanciaMedia=0;
		this.tagMedidaConstruccion.val(0);
		this.data.medidaConstruccion=0;
		this.tagCoordInicial.text("");
		this.data.CoordInicial = [];
		this.tagCoordFinal.text("");
		this.data.CoordFinal = [];
		this.tagTipoConstruccion.val("1");
		this.data.tipo = {idtipo:"1", nombre:"Habitacional"};
		this.tagUbicacion.eq(0).closest('label').removeClass('active');
		this.tagUbicacion.eq(1).closest('label').addClass('active');
		this.data.ubicacionLado = "derecha";
		this.tagPosicionNivel.eq(2).closest('label').addClass('active');
		this.tagPosicionNivel.eq(1).closest('label').removeClass('active');
		this.tagPosicionNivel.eq(0).closest('label').removeClass('active');
		this.data.posicionNivel = "carretera"; 
		this.tagGrupoConstrucciones.eq(0).closest('label').addClass('active');
		this.tagGrupoConstrucciones.eq(1).closest('label').removeClass('active');
		this.data.grupo = "false"; 

		this.tagCurvatura.val(1);
		this.data.curvatura = {id:1,descripcion:"Recta o ligeramente curva"};

		this.tagAcotamiento.val(2);
		this.data.acotamiento = {id:2,descripcion:"1.0 a 2.5"};

		this.tagDispositivos.val(0);
		this.data.dispositivos = {id:0,descripcion:"Ninguno"};

		this.tagBandasDeAlerta.eq(1).prop('checked', true);
		this.data.bandasDeAlerta = false;

		this.tagNivelConstruccion.val("1");
		this.data.niveles = "1"; 
		
		this.tagElemNoAbatibles.val("0");

		$('.noAbatibles-text, .noAbatibles-select').remove();
		this.data.noAbatibles = [];
		
		 this.unsetMarkers();			
		 if(this.linea != undefined){
		 	  	this.linea.setMap(null);
		 }			
		 this.markers = [];
		 this.cantMarkers = 0;

		 $(".showMedicionesRubro").attr('checked', false);
		 
	},
	unsetMarkers: unsetMarkers,
	exit: function(){
		    this.unsetMarkers();			
		    if(this.linea != undefined){
		    	this.linea.setMap(null);
		    }			
			this.markers = [];
			this.cantMarkers = 0;
			this.clearTags();

			_getMedidasAnteriores.clear();
		
	},
	save: function(btn){
		
		var error=false;
		error = (this.data.cadInicial==0 || error) ? true:false;
		error = (this.data.cadFinal==0 || error) ? true:false;
		error = (this.data.distanciaMedia==0 || error) ? true:false;
		error = (this.data.medidaConstruccion==0 || error) ? true:false;
		error = (this.data.CoordInicial==0 || error) ? true:false;
		error = (this.data.CoordFinal==0 || error) ? true:false;

		if(error == true){
			alert("Los campos de medición son requeridos");
			return false;
		}
		
		this.data.rubro = "construcciones";
		this.data.tramo = _parentEstacion.tramo;
		this.data.sentido = _parentEstacion.sentido;
		this.data.carril = _parentEstacion.carril;
		this.data.screenshot = c.toDataURL();	
		this.data.cadenamiento = imagesNavigation.estacion.cad;
		this.data.idEstacion = imagesNavigation.idEstacion;		
		
		_this = this;		
		$.post('includes/construcciones.php', this.data, function(data, textStatus, xhr) {
				
			btn.attr('disabled', false);
			btn.text('Guardar');

			var response = jQuery.parseJSON(data);
			console.log("response",response);
			if(response.file != undefined){
				alert("Los datos se guardaron correctamente");
				$("#screenshot-modal").modal("hide"); 
				
				if(response.file.updateTime == undefined){
					_this.clearTags();	
				}		
				
			}else{
				alert("Error al intentar guardar.");	
			}				 
		});

	},
	mensajes: function(){	

		return {
			labels:[
				{titulo:"Tramo", x:700, y:350},
		  		{titulo:"Sentido", x:700, y:400},
		  		{titulo:"Carril", x:700, y:450},
		  		{titulo:"Cad. Inicial", x:700, y:500},
		  		{titulo:"Cad. Final", x:700, y:550},
		  		{titulo:"Ubicacion", x:900, y:350},
		  		{titulo:"Medida", x:900, y:400},
		  		{titulo:"Distancia", x:900, y:450},
		  		{titulo:"Nivel", x:900, y:500},
		  		{titulo:"Tipo", x:900, y:550}	
			],
			values:[
				{texto:_parentEstacion.tramo, x:795, y:350},
		  		{texto:_parentEstacion.sentido, x:795, y:400},
		  		{texto:_parentEstacion.carril, x:795, y:450},
		  		{texto:this.data.cadInicial, x:795, y:500},
		  		{texto:this.data.cadFinal, x:795, y:550},
		  		{texto:this.data.ubicacionLado, x:995, y:350},
		  		{texto:this.data.medidaConstruccion, x:995, y:400},
		  		{texto:this.data.distanciaMedia, x:995, y:450},
		  		{texto:this.data.niveles, x:995, y:500},
		  		{texto:this.data.tipo.nombre, x:995, y:550}	
			]
		}

	    
  	}
};
//////////////////////////////////////////////
/////////INTERSECCIONES

var interseccionProp  = {
	      		map: this.map,
	      		title:"Interseccion",
	      		name:"click1",
	      		position : '',
	      		icon: imageDistancia,
	      		init: function(parent){
	      			    this.parent = parent;
	      				cadMasCercano(this,parent);
	      			},
	      		setTags: function(triangulo){	
	      			triangulo.newCadenamiento = (triangulo.newCadenamiento).toFixed(4);

	      			this.parent.tagCadKm.val(triangulo.newCadenamiento);
	      			this.parent.tagCadKmText.text(this.getPosition());

	      			this.parent.data.cadCarretera = triangulo.newCadenamiento;
	      			this.parent.data.coordenadas = [this.getPosition().lng(),this.getPosition().lat()];
	      				
	      		}	
	      	};


var interseccionesProto = {
	rubro:"intersecciones",
	tagForm:$("#interseccionesForm"),
	tagCadKm:$('#interseccionesForm input[name=cadenamientoKm]'),
	tagTipoInterseccion:$('#interseccionesForm input[name=tipoInterseccion]'),
	tagTipoSolucion:$('#interseccionesForm [name=tipoSolucion]'),
	tagPoblacionIzquierda:$('#interseccionesForm input[name=poblacionIzquierda]'),
	tagPoblacionDerecha:$('#interseccionesForm input[name=poblacionDerecha]'),
	tagCadKmText:$('#interseccionesForm #cadKmText'),
	tagClearFormInt:$('#clearFormInt'),
	data:{screenshot:''},
	markers:[],
	map: map,
	setMarker : setMarker,	
	unsetMarkers : unsetMarkers,
	calcularTriangulo : calcularTriangulo,
	interseccionProp : interseccionProp,
	clickMap: function(event){
		if (this.markers.length == 1) {			
			this.unsetMarkers();			
			this.markers = [];		
		}	
		this.interseccionProp.position = event.latLng;
		var marker = this.setMarker(interseccionProp);
			marker.init(this);
		this.markers.push(marker);
	},
	addFormEvents: function(){
		var _this = this;
		this.tagTipoInterseccion.change(function(event) {
			_this.data.tipo = $(this).val();
		});	

		this.tagTipoSolucion.change(function(event) {
			_this.data.tipoSolucion = $(this).find("option:selected").data();			
		});
		this.tagClearFormInt.click(function(event) {
			event.preventDefault();
			_this.clearTags();
		});

	},
	init : function(){
		this.addFormEvents();
		this.clearTags();
	},
	exit: function(){
		this.unsetMarkers();
		//this.linea.setMap(null);
		this.markers = [];
		_getMedidasAnteriores.clear();
		//return confirm("seguro que desea borrar mediciones en "+ this.rubro +"?");
	},
	clearTags : function(){
		this.tagCadKm.val(0);
		this.tagTipoInterseccion.eq(0).closest('label').addClass('active');
		this.tagTipoInterseccion.eq(1).closest('label').removeClass('active');
		this.tagTipoSolucion.val(1);
		this.tagPoblacionIzquierda.val("");
		this.tagPoblacionDerecha.val("");
		this.tagCadKmText.text("");	

		this.data.cadCarretera = 0;
		this.data.coordenadas = [];
		this.data.tipo = "cruce";
		this.data.destinos = [];
		this.data.tipoSolucion = {idtipo:"1",descripcion:"Nivel"};

		 this.unsetMarkers();			
		 if(this.linea != undefined){
		 	  	this.linea.setMap(null);
		 }			
		 this.markers = [];
		 $(".showMedicionesRubro").attr('checked', false);
	},
	save : function(btn){
		var poblacionIzquierda, poblacionDerecha, destinos=[];
		poblacionIzquierda = {direccion:"izquierda",poblacion:this.tagPoblacionIzquierda.val()};
		poblacionDerecha = {direccion:"derecha",poblacion:this.tagPoblacionDerecha.val()};	

		destinos.push(poblacionIzquierda);
		destinos.push(poblacionDerecha);

		this.data.destinos = destinos;

		this.data.rubro = "intersecciones";
		this.data.tramo = _parentEstacion.tramo;
		this.data.sentido = _parentEstacion.sentido;
		this.data.carril = _parentEstacion.carril;
		this.data.screenshot = c.toDataURL();
		this.data.cadenamiento = _parentEstacion.cad;
		this.data.idEstacion = imagesNavigation.idEstacion;	

		var _this = this;

		var error=false;
		error = (this.data.cadCarretera==0 || error) ? true:false;
		
		if(error == true){
			alert("Los campos de medición son requeridos");
			return false;
		}

		$.post('includes/intersecciones.php', this.data, function(data, textStatus, xhr) {
			btn.attr('disabled', false);
			btn.text('Guardar');
			var response = jQuery.parseJSON(data);
			if(response.file != undefined){
				alert("Los datos se guardaron correctamente");				
				$("#screenshot-modal").modal("hide");				
				
				//clear tag only on save event
				if(response.file.updateTime == undefined){
					_this.clearTags();	
				}

			}else{
				alert("Error al intentar guardar.");	
			}
		});		

	},
	mensajes: function(){	
		return {
			labels:[
			   {titulo:"Tramo", x:700, y:350},
	  		   {titulo:"Sentido", x:700, y:400},
	  		   {titulo:"Carril", x:700, y:450},
	  		   {titulo:"Cadenamiento", x:700, y:500},
	  		   {titulo:"Tipo", x:900, y:350},
	  		   {titulo:"Solucion", x:900, y:400}
			],
			values:[
			   {texto:_parentEstacion.tramo, x:795, y:350},
	  		   {texto:_parentEstacion.sentido, x:795, y:400},
	  		   {texto:_parentEstacion.carril, x:795, y:450},
	  		   {texto:this.data.cadCarretera, x:830, y:500},
	  		   {texto:this.data.tipo, x:995, y:350},
	  		   {texto:this.data.tipoSolucion.descripcion, x:995, y:400}
			]
		};	   
  	}
};
//////////////////////////////////****************************/////////////////////////
var accesoProp  = {
	      		map: this.map,
	      		title:"Acceso Informal",
	      		name:"click1",
	      		position : '',
	      		icon: imageDistancia,
	      		init: function(parent){
	      			    this.parent = parent;
	      				cadMasCercano(this,parent);
	      			},
	      		setTags: function(triangulo){
	      			 
	      			console.log("t",triangulo);
	      		}	
	     };

var accesosProto =  {
	rubro:"accesos irregulares",
	tagForm:$("#accesosForm"),
	tagCadKm:$('#accesosForm input[name=cadenamientoKm]'),
	tagTipoInterseccion:$('#accesosForm input[name=tipoInterseccion]'),	
	tagTipoAcceso : $('#accesosForm input[name=tipoAcceso]'),
	tagPoblacionIzquierda:$('#accesosForm input[name=poblacionIzquierda]'),
	tagPoblacionDerecha:$('#accesosForm input[name=poblacionDerecha]'),
	tagPropiedades : $('#accesosForm [name=propiedades]'),
	tagViaVisible : $('#accesosForm [name=viaVisible]'),
	tagCadKmText:$('#accesosForm #cadKmText'),
	tagClearFormInt:$('#clearFormAccess'),
	data:{screenshot:''},
	markers:[],
	map: map,
	setMarker : setMarker,	
	unsetMarkers : unsetMarkers,
	calcularTriangulo : calcularTriangulo,
	interseccionProp : interseccionProp,
	clickMap: function(event){
		if (this.markers.length == 1) {			
			this.unsetMarkers();			
			this.markers = [];		
		}	
		this.interseccionProp.position = event.latLng;
		var marker = this.setMarker(interseccionProp);
			marker.init(this);
		this.markers.push(marker);
	},
	addFormEvents: function(){
		var _this = this;
		this.tagTipoInterseccion.change(function(event) {
			_this.data.tipo = $(this).val();
		});
		
		this.tagTipoAcceso.change(function(event) {
			_this.data.tipoAcceso = $(this).val();
		});

		this.tagPropiedades.change(function(event) {
			_this.data.propiedades = $(this).find("option:selected").data();	
		});

		this.tagViaVisible.change(function(event) {
			_this.data.viaVisible = $(this).val();
		});

		this.tagClearFormInt.click(function(event) {
			event.preventDefault();
			_this.clearTags();
		});

	},
	init : function(){
		this.addFormEvents();
		this.clearTags();
	},
	exit: function(){
		this.unsetMarkers();
		//this.linea.setMap(null);
		this.markers = [];
		_getMedidasAnteriores.clear();
		//return confirm("seguro que desea borrar mediciones en "+ this.rubro +"?");
	},
	clearTags : function(){
		this.tagCadKm.val(0);
		
		this.tagTipoInterseccion.eq(0).closest('label').addClass('active');
		this.tagTipoInterseccion.eq(1).closest('label').removeClass('active');

		this.tagTipoAcceso.eq(0).closest('label').addClass('active');
		this.tagTipoAcceso.eq(1).closest('label').removeClass('active');
		this.data.tipoAcceso = "formal";

		this.tagPoblacionIzquierda.val("");
		this.tagPoblacionDerecha.val("");
		this.tagCadKmText.text("");	

		this.tagPropiedades.val(0);
		this.data.propiedades = {idTipo:0,descripcion:'Ninguna'};

		this.tagViaVisible.val(0);
		this.data.viaVisible = 0;

		this.data.cadCarretera = 0;
		this.data.coordenadas = [];
		this.data.tipo = "cruce";		
		this.data.destinos = [];	

		 this.unsetMarkers();			
		 if(this.linea != undefined){
		 	  	this.linea.setMap(null);
		 }			
		 this.markers = [];	
		 $(".showMedicionesRubro").attr('checked', false);
	},
	save : function(btn){
		var poblacionIzquierda, poblacionDerecha, destinos=[];
		poblacionIzquierda = {direccion:"izquierda",poblacion:this.tagPoblacionIzquierda.val()};
		poblacionDerecha = {direccion:"derecha",poblacion:this.tagPoblacionDerecha.val()};	

		destinos.push(poblacionIzquierda);
		destinos.push(poblacionDerecha);

		this.data.destinos = destinos;

		this.data.rubro = "accesos irregulares";
		this.data.tramo = _parentEstacion.tramo;
		this.data.sentido = _parentEstacion.sentido;
		this.data.carril = _parentEstacion.carril;
		this.data.screenshot = c.toDataURL();
		this.data.cadenamiento = _parentEstacion.cad;
		this.data.idEstacion = imagesNavigation.idEstacion;	

		var _this = this;

		var error=false;
		error = (this.data.cadCarretera==0 || error) ? true:false;
		
		if(error == true){
			alert("Los campos de medición son requeridos");
			return false;
		}

		$.post('includes/accesos.php', this.data, function(data, textStatus, xhr) {
			btn.attr('disabled', false);
			btn.text('Guardar');
			var response = jQuery.parseJSON(data);
			if(response.file != undefined){
				alert("Los datos se guardaron correctamente");				
				$("#screenshot-modal").modal("hide"); 

				//clear tag only on save event
				if(response.file.updateTime == undefined){
					_this.clearTags();	
				}	
				
			}else{
				alert("Error al intentar guardar.");	
			}	
		});
	},
	mensajes: function(){
		return {
			labels:[
			   {titulo:"Tramo", x:700, y:350},
	  		   {titulo:"Sentido", x:700, y:400},
	  		   {titulo:"Carril", x:700, y:450},
	  		   {titulo:"Cadenamiento", x:700, y:500},
	  		   {titulo:"Tipo", x:900, y:350},
	  		   {titulo:"Propiedades", x:900, y:400},
	  		   {titulo:"Distancia derecho de via", x:900, y:450} 
			],
			values:[
			   {texto:_parentEstacion.tramo, x:795, y:350},
	  		   {texto:_parentEstacion.sentido, x:795, y:400},
	  		   {texto:_parentEstacion.carril, x:795, y:450},
	  		   {texto:this.data.cadCarretera, x:830, y:500},
	  		   {texto:this.data.tipoAcceso, x:1010, y:350},
	  		   {texto:this.data.propiedades.descripcion, x:1010, y:400},
	  		   {texto:this.data.viaVisible, x:1100, y:450} 
			]
		};	    
  	}
};

/************************* *******************/
var cortesTerrapletesProp  = [
			{
		     	map: this.map,
		     	title:"corte o terraple",
		     	name:"click1",
		     	position : '',
		     	icon: imageInicial,
		     	init: function(parent){
		     		    this.parent = parent;
		     			cadMasCercano(this,parent);
		     		},
		     	setTags: function(triangulo){
		     		console.log(triangulo);
		     		triangulo.newCadenamiento = (triangulo.newCadenamiento).toFixed(4);
		     		this.parent.tagCadInicial.val(triangulo.newCadenamiento);
		     		this.parent.tagCadInicialText.text(this.getPosition());

		     		this.parent.data.cadCarretera.inicial=triangulo.newCadenamiento;
		     		this.parent.data.coordenadas.inicial=[this.getPosition().lng(),this.getPosition().lat()];
		     	}	
		     },

		     {
		     	map: this.map,
		     	title:"corte o terraple",
		     	name:"click1",
		     	position : '',
		     	icon: imageFinal,
		     	init: function(parent){
		     		    this.parent = parent;
		     			cadMasCercano(this,parent);
		     		},
		     	setTags: function(triangulo){		     		

		     		var color = (this.parent.data.tipo == 'terraplen')? 'blue' : 'orange'; 

					this.parent.linea = this.parent.setLine(color);

					triangulo.newCadenamiento = (triangulo.newCadenamiento).toFixed(4);
					//calcular cad inical y final 
					if (triangulo.newCadenamiento > this.parent.data.cadCarretera.inicial){

						this.parent.tagCadFinal.val(triangulo.newCadenamiento);
			     		this.parent.tagCadFinalText.text(this.getPosition());

			     		this.parent.data.cadCarretera.final = triangulo.newCadenamiento;
			     		this.parent.data.coordenadas.final = [this.getPosition().lng(),this.getPosition().lat()];	
					}else{    		


						var coInicial = this.parent.data.coordenadas.inicial,
							cadInicial = this.parent.data.cadCarretera.inicial;

			     		//inicial
			     		this.parent.data.cadCarretera.inicial=triangulo.newCadenamiento;
			     		this.parent.data.coordenadas.inicial=[this.getPosition().lng(),this.getPosition().lat()];

			     		this.parent.tagCadInicial.val(triangulo.newCadenamiento);
			     		this.parent.tagCadInicialText.text(this.getPosition());	

			     		//final
						this.parent.data.cadCarretera.final = cadInicial;
			     		this.parent.data.coordenadas.final = coInicial;	

						this.parent.tagCadFinal.val(cadInicial);
			     		this.parent.tagCadFinalText.text('('+coInicial[1]+',\n'+coInicial[0]+')');
	     		

					}				
							     		

					var medida = (this.parent.data.cadCarretera.final - this.parent.data.cadCarretera.inicial).toFixed(4),
						medidaR = distHaversine(this.parent.markers[0].getPosition(),this.parent.markers[1].getPosition());

					console.log("distancia corte/terraplen",distHaversine(this.parent.markers[0].getPosition(),this.parent.markers[1].getPosition()));	
					console.log("distancia con respecto a la carretera",medida);

					this.parent.tagMedida.val(medida);
					this.parent.data.medida = medida;

					//$('#mcrcarreteraCT').text('Con respecto a la carretera: '+medida);

		     	}	
		     }
	     ];

var cortesTerraplenesProto = {
	rubro:"Cortes terraplenes",	
	tagForm: $('#cortesTerraplenesForm'),
	tagCadInicial:$('#cortesTerraplenesForm input[name=cadenamientoInicial]'),
	tagCadFinal:$('#cortesTerraplenesForm input[name=cadenamientoFinal]'),
	tagMedida : $('#cortesTerraplenesForm input[name=medida]'),
	tagTipo:$('#cortesTerraplenesForm input[name=tipo]'),
	tagUbicacion:$('#cortesTerraplenesForm input[name=ubicacion]'),
	tagCadInicialText:$('#cortesTerraplenesForm .cadInicialText'),
	tagCadFinalText:$('#cortesTerraplenesForm .cadFinalText'),
	tagCortesTerraplenesLimpiar:$("#cortesTerraplenesLimpiar"),
	markers:[],
	data:{cadCarretera:{},coordenadas:{},screenshot:''},
	map: map,
	setMarker : setMarker,	
	setLine : setLine,
	unsetMarkers : unsetMarkers,
	calcularTriangulo : calcularTriangulo,
	cortesTerrapletesProp : cortesTerrapletesProp,
	clickMap: function(event){
		if (this.markers.length == 2) {			
			this.unsetMarkers();			
			this.markers = [];		
			this.linea.setMap(null);
		}	
		var props = this.cortesTerrapletesProp[this.markers.length];
		props.position = event.latLng;
		var marker = this.setMarker(props);
			marker.init(this);
		this.markers.push(marker);		
	},
	init : function(){
		this.addFormEvents();
		this.clearTags();     
	},
	addFormEvents : function(){
		var _this = this;
		this.tagTipo.change(function(event) {
			_this.data.tipo = $(this).val();				
		});	
		this.tagUbicacion.change(function(event) {
			_this.data.ubicacionLado = $(this).val();			
		});
		this.tagCortesTerraplenesLimpiar.click(function(event) {
			event.preventDefault();
			_this.clearTags();
		});
	},
	exit: function(){
		this.unsetMarkers();
		if(this.linea != undefined){
		  	this.linea.setMap(null);
		}
		this.markers = [];
		_getMedidasAnteriores.clear();
		//return confirm("seguro que desea borrar mediciones en "+ this.rubro +"?");
	},
	clearTags: function(){
		this.tagCadInicial.val(0);
		this.tagCadFinal.val(0);		
		this.tagTipo.eq(0).closest('label').addClass('active');
		this.tagTipo.eq(1).closest('label').removeClass('active');
		this.tagUbicacion.eq(0).closest('label').removeClass('active');
		this.tagUbicacion.eq(1).closest('label').addClass('active');
		this.tagCadInicialText.text("");
		this.tagCadFinalText.text("");

		this.tagMedida.val(0);
		this.data.medida = 0;	

		this.data.tipo = "corte";
		this.data.ubicacionLado = "derecha";
		this.data.cadCarretera = {};
		this.data.coordenadas = {};

		 this.unsetMarkers();			
		 if(this.linea != undefined){
		 	  	this.linea.setMap(null);
		 }			
		 this.markers = [];
		 $(".showMedicionesRubro").attr('checked', false);
	},
	save: function(btn){

		this.data.rubro = this.data.tipo;
		this.data.tramo = _parentEstacion.tramo;
		this.data.sentido = _parentEstacion.sentido;
		this.data.carril = _parentEstacion.carril;
		this.data.screenshot = c.toDataURL();	
		this.data.cadenamiento = _parentEstacion.cad;
		this.data.idEstacion = imagesNavigation.idEstacion;

		var _this = this;
		var error=false;		
		
		error = (this.data.cadCarretera.inicial==undefined || this.data.cadCarretera.final==undefined ||error) ? true:false;
		
		if(error == true){
			alert("Los campos de medición son requeridos");
			return false;
		}
		
		$.post('includes/cortes.php', this.data, function(data, textStatus, xhr) {
			btn.attr('disabled', false);
			btn.text('Guardar');
			var response = jQuery.parseJSON(data);
			if(response.file != undefined){
				alert("Los datos se guardaron correctamente");				
				$("#screenshot-modal").modal("hide");

				//clear tag only on save event
				if(response.file.updateTime == undefined){
					_this.clearTags();	
				} 
				
			}else{
				alert("Error al intentar guardar.");	
			}
		});
	},
	mensajes: function(){
		return {
			labels : [
			   {titulo:"Tramo", x:700, y:350},
	  		   {titulo:"Sentido", x:700, y:400},
	  		   {titulo:"Carril", x:700, y:450},
	  		   {titulo:"Cad. Inicial", x:700, y:500},
	  		   {titulo:"Cad. Final", x:700, y:550},
	  		   {titulo:"Medida", x:900, y:350},
	  		   {titulo:"Tipo", x:900, y:400},
	  		   {titulo:"Posicion", x:900, y:450}
			],
			values : [
			   {texto:_parentEstacion.tramo, x:795, y:350},
	  		   {texto:_parentEstacion.sentido, x:795, y:400},
	  		   {texto:_parentEstacion.carril, x:795, y:450},
	  		   {texto:this.data.cadCarretera.inicial, x:795, y:500},
	  		   {texto:this.data.cadCarretera.final, x:795, y:550},
	  		   {texto:this.data.medida, x:995, y:350},
	  		   {texto:this.data.tipo, x:995, y:400},
	  		   {texto:this.data.ubicacionLado, x:995, y:450}
			]
		};	    
  	} 
};

var rubros = {
	tabConstrucciones: construccionesProto,
	tabIntersecciones: interseccionesProto,
	tabAccesos : accesosProto,
	tabCortesTerraplenes : cortesTerraplenesProto
};

var currentRubro = Object.create(rubros.tabConstrucciones);
	currentRubro.init();


$(".tabRubros").click(function(event) {
	currentRubro.exit();
	var id = $(this).attr('id');
	currentRubro = Object.create(rubros[id]);
	currentRubro.init();
});

google.maps.event.addListener(map, 'click', function(event){
	currentRubro.clickMap(event);
	placeMarker(currentRubro.markers);
});	


$("#saveCurrentRubro").click(function(event) {
	currentRubro.save($(this));
	$(this).attr('disabled', true);
	$(this).text('Guardando...');

});


$(".showMedicionesRubro").change(function(event) {
	var show = $(this)[0].checked,
		rubro = currentRubro.rubro;				

		if(rubro == "Cortes terraplenes"){
			rubro = "corte";
			_getMedidasAnteriores.init(show,rubro);
			rubro = "terraplen";
			_getMedidasAnteriores.init(show,rubro);
			return false;			
		}

		_getMedidasAnteriores.init(show,rubro);				
		
});
