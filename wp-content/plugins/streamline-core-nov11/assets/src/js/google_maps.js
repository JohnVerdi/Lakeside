var streamline_map = {
	map : null,
	infowindow : null,
	bounds : null,
	initMap : function(){
		bounds = new google.maps.LatLngBounds();
		map = new google.maps.Map(document.getElementById(streamline_gmap.map_id), {
	    	center: {lat: 0, lng: 0},
	    	zoom: 8
	  	});
		infowindow = null;
	  	streamline_map.setMarkers(map, streamline_gmap.places);	  	
	},
	setMarkers : function(map, places){
		for (i = 0; i < places.length; i++)
		{
			place = places[i];	
			if (place.latlng)
			{				
				streamline_map.putMarker(map,new google.maps.LatLng(place.latlng[0],place.latlng[1]),place.title,place.html);			
			}
		}
	},
	putMarker : function(map, coords, title, contents){
		bounds.extend(coords);
		map.fitBounds(bounds);

		var marker;
		if(streamline_gmap.icon != 'default'){
			marker = new google.maps.Marker({position: coords, map: map, title: title, icon : streamline_gmap.icon });
		}else{
			marker = new google.maps.Marker({position: coords, map: map, title: title});
		}	
		
		google.maps.event.addListener(marker, 'click', function() {
			if (infowindow)
				infowindow.close();
			infowindow = new google.maps.InfoWindow({content: contents, maxWidth: 300});
			infowindow.open(map,marker);
		});
		return;
	}
};

document.addEventListener('DOMContentLoaded', streamline_map.initMap );