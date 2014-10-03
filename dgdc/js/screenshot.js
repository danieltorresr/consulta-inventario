var map;
var mapa = {
 	x:640,
 	y:300,
 	markers:[],
 	addMarker:function(marker){
 		this.markers.push(marker);
 	},
 	useMap : function(mapa){
 		this.mapa = mapa;
 	},
	url: function(){
		var url="http://maps.googleapis.com/maps/api/staticmap?";
        url += this.mapCenter()+this.zoom()+this.size()+this.urlMarkers()+this.urlPolyPath()+"maptype=hybrid&sensor=false&key=AIzaSyDGAc2LNcqcMIqjFIocrQWZ_HYsaKvtpRQ";
		return url;
	},
	mapCenter : function(){
	    return	"center="+this.mapa.getCenter().lat()+","+this.mapa.getCenter().lng()+"&";
	},
	zoom : function(){
	    return	"zoom="+this.mapa.getZoom()+"&";
	},
	size : function(){
	    return	"size="+this.x+"x"+this.y+"&";
	},
	setMapa: function(elemento){
		
		elemento.attr({
			width: this.x+'px',
			height: this.y+'px',
			src: this.url()
		});
		elemento.appendTo('body');
	},
	urlMarkers:function(){
		var url="";
		$.each(this.markers, function(index, val) {
			 /* iterate through array or object */
			 var urlRed ="markers="+val.getPosition().lat()+","+val.getPosition().lng()+"&";
			url += urlRed;			
		});

		return url;
	},
	urlPolyPath: function(){
		//|fillcolor:0xFFFF0033"
		var path = "path=color:0x0000ff|weight:5"; 
		$.each(this.markers, function(index, val) {
			 /* iterate through array or object */
			 if(index < 2){
			 	var urlPath ="|"+val.getPosition().lat()+","+val.getPosition().lng();
			 	path = 	path + 	urlPath;	 	
			 }			 
						
		});

		return path+"&";
	}

}



function placeMarker(markers) {
   /* var marker = new google.maps.Marker({
      		        position: location, 
            	  	map: map
                });*/
    mapa.markers = markers;    
}

var c=document.getElementById("canvas-screenshot"),
	static_map = document.getElementById("static-map"), 
	izquierda=document.getElementById("imgizq"), 
	central=document.getElementById("imgcen"),
	derecha=document.getElementById("imgder");

 
static_map.crossOrigin= "anonymous";
//izquierda.crossOrigin= "anonymous";
// central.crossOrigin= "anonymous";

$("#generate-static-map").click(function(event) {
	
  /* Act on the event */
  //$('#urlMap').text(mapa.url());
  mapa.setMapa($("#static-map"));
  $("#screenshot-progress-modal").modal("show");
  setTimeout(function(){
  	renderizar(currentRubro.mensajes());
  },5000);

});


var renderizar = function(mensajes) {
  /* Act on the event */
 
	var ctx=c.getContext("2d");	
	 ctx.rect(0,0,1200,600);
	 ctx.fillStyle = 'white';
	 ctx.fill();

	 //ctx.strokeStrike='black';
	 //ctx.stroke();
	 
	 //ctx.fillStyle = 'black';

	 ctx.drawImage(izquierda,0,0,412,315);
	 ctx.drawImage(central,400,0,400,300);
	 ctx.drawImage(derecha,790,0,400,312);
	 ctx.drawImage(static_map,0,300,640,300);
	 ctx.font = "bold 16px Arial";
	// ctx.fillStyle = 'black';


	// fix to align images
	ctx.fillStyle = 'white';
	ctx.beginPath();

	ctx.rect(645,300,550,30);
	ctx.fill();

	ctx.strokeStrike='white';
	//ctx.stroke();
	// end //

	ctx.font = 'bold 16px Century Gothic';
	ctx.fillStyle = 'black';

	var labels = mensajes.labels,
		values = mensajes.values;

	//set labels
	ctx.fillStyle = '#3961b0';
	for (var i = 0; i < labels.length; i++) {
		ctx.fillText(labels[i].titulo+" : ", labels[i].x, labels[i].y);
	}

	//set values
	ctx.fillStyle = 'black';
	for (var j = 0; j < values.length; j++) {
		ctx.fillText(values[j].texto, values[j].x, values[j].y);
	}

	  //show preview
	$("#screenshot-progress-modal").modal("hide");
	$("#screenshot-preview-img").attr('src',c.toDataURL());
	$("#screenshot-modal").modal("show");  
}