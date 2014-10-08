
var imagesNavigation = {
	btnPrimera : $('#primerImagen'),
	btnAnterios : $('#anteriorImagen'),
	btnSiguiente : $('#siguienteImagen'),
	btnUltima : $('#ultimaImagen'),
	btnSearch : $('.search-cad-img'),
	cadToSearch : $('input[name=searchText]'), 
	imgizquierda : $('#imgizq'),
	imgderecha : $('#imgder'),
	imgcentral : $('#imgcen'),
	fastNavigaton : $('#fastNavigaton'),	
	fastNavigatonNum : 0,
	saveUbication : $('#saveUbication'),
	getSaveubication : $('#getSaveubication'),
	estacion :_parentEstacion,
	idEstacion : null,
	map:null,
	currentMarker : new google.maps.Marker(),
	// title info 
	info : $('.info'),
	init: function(){		
		this.events();
		//this.first();
	},
	load: function(){			
		var _this = this;		
		$.getJSON('includes/getCamaras.php', _this.estacion, function(imagenesjson, textStatus) {     		
     		_this.estacion.cad = imagenesjson.meta.cadGeometrico + _this.fastNavigatonNum;     		
     		_this.cadToSearch.val(_this.estacion.cad);     		
     		for (var lado in imagenesjson) {       				 			
	     			if (lado != "meta") {	     					     				
	     				_this["img"+lado].attr('src', imagenesjson[lado]);
	     			}     			
			   }			  
			_this.idEstacion = imagenesjson.meta.idEstacion;     
			_this.currentOnMap(imagenesjson.meta.longitud,imagenesjson.meta.latitud);   			    
     	});     	
	},
	setTitle : function(data){
		console.log('dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',data);
		this.info.find('.carretera_label').text(data.carretera);
		this.info.find('.sentido_label').text('S'+data.sentido);
		this.info.find('.carril_label').text('C'+data.carril);
		this.info.find('.tramo_label').text(data.tramo);
	},
	next: function(){
		
	},
	prev: function(){
		var _this = this;
		$.getJSON('includes/getCamarasAnterior.php', _this.estacion, function(imagenesjson, textStatus) {     		
     		_this.estacion.cad = imagenesjson.meta.cadGeometrico  - _this.fastNavigatonNum;
     		_this.cadToSearch.val(_this.estacion.cad);     		
     		for (var lado in imagenesjson) {     			
	     			if (lado != "meta") {
	     				_this["img"+lado].attr('src', imagenesjson[lado]);
	     			}     			
			   }
			_this.idEstacion = imagenesjson.meta.idEstacion;     			
			_this.currentOnMap(imagenesjson.meta.longitud,imagenesjson.meta.latitud);  
     	}); 
	},
	first: function(){		
		var _this = this;
		$.getJSON('includes/getCamarasPrimero.php', _this.estacion, function(imagenesjson, textStatus) {     		
     		_this.estacion.cad = imagenesjson.meta.cadGeometrico; 
     		_this.cadToSearch.val(_this.estacion.cad);    		
     		for (var lado in imagenesjson) {     			
	     			if (lado != "meta") {	     				
	     				_this["img"+lado].attr('src', imagenesjson[lado]);
	     			}	     			   			
			   }			   
			_this.idEstacion = imagenesjson.meta.idEstacion;     
			_this.currentOnMap(imagenesjson.meta.longitud,imagenesjson.meta.latitud);    
     	});
	},
	last: function(){
		var _this = this;
		$.getJSON('includes/getCamarasUltimo.php', _this.estacion, function(imagenesjson, textStatus) {     		
     		_this.estacion.cad = imagenesjson.meta.cadGeometrico; 
     		_this.cadToSearch.val(_this.estacion.cad);     		
     		for (var lado in imagenesjson) {     			
	     			if (lado != "meta") {
	     				_this["img"+lado].attr('src', imagenesjson[lado]);
	     			}	     			   			
			   }
			_this.idEstacion = imagenesjson.meta.idEstacion;     
			_this.currentOnMap(imagenesjson.meta.longitud,imagenesjson.meta.latitud);   
     	});
	},
	events: function(){
		var _this = this;
		_this.btnSiguiente.click(function(event) {
			_this.load();
		});

		_this.btnAnterios.click(function(event) {
			_this.prev();
		});

		_this.btnPrimera.click(function(event) {
			_this.first();
		});

		_this.btnUltima.click(function(event) {
			_this.last();
		});
		
		_this.btnSearch.click(function(event) {
			_this.estacion.cad = _this.cadToSearch.val();
			_this.search();
		});

		_this.cadToSearch.keypress(function(event) {
			if ( event.which == 13 ) {
				_this.estacion.cad = $(this).val();
				_this.search();
			    event.preventDefault();
			}	
		});

		_this.fastNavigaton.find('input').change(function(event) {
			var value = $(this).is(":checked");
			_this.fastNavigatonNum = (value)? 100 : 0;				

		});

		_this.saveUbication.click(function(event) {			
			localStorage.setItem("currentUbication", _this.estacion.cad);	
			alert("ubicacion actual guardada correctamente");
		});

		_this.getSaveubication.click(function(event) {			
			if(localStorage.getItem("currentUbication")){
				_this.estacion.cad = localStorage.getItem("currentUbication");	
				_this.cadToSearch.val(_this.estacion.cad);
				_this.search();
			}			
		});
	},
	currentOnMap: function(lng,lat){		
		this.currentMarker.setMap(null);
		
		var _this = this;		
			this.currentMarker = new google.maps.Marker({
				icon: imagePosicion,
				map: _this.map,
				title:"seguimiento",				      		
				position:{lng:lng,lat:lat} 
			});

		this.map.setCenter(this.currentMarker.getPosition());
		
	},
	search : function(){
		var _this = this;	
		console.log("_this.estacion",_this.estacion);	
		$.getJSON('includes/searchCamaras.php', _this.estacion, function(imagenesjson, textStatus) {
			console.log("imagenesjson",imagenesjson);     		
     		_this.estacion.cad = imagenesjson.meta.cadGeometrico;     		   		
     		for (var lado in imagenesjson) {     			
	     			if (lado != "meta") {
	     				_this["img"+lado].attr('src', imagenesjson[lado]);
	     			}     			
			   }
			_this.currentOnMap(imagenesjson.meta.longitud,imagenesjson.meta.latitud);   
			_this.setTitle(imagenesjson.meta); 			    
     	});		
	},
	error: function(){

	} 
}