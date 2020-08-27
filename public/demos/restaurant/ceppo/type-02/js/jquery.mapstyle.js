	function initialize() {
    var styles = [
		{ "stylers": [{ "saturation": -100 }, { "gamma": 1 }] }, 
		{ "featureType": "water", "stylers": [{ "lightness": -12 }] }
	];

    var styledMap = new google.maps.StyledMapType(styles, { name: "Styled Map" });
    var latlng = new google.maps.LatLng(34.694490, 135.195192,15);

    var myOptions = { 
		scrollwheel: false, 
		zoom: 17, 
		center: latlng, 
		mapTypeControlOptions: { 
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'] 
		} 
	};

    var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
		
    var image = { url: 'http://okirakun.com/demos/ceppo/image/mapicons/MapIcon.png', 
				 size: new google.maps.Size(70, 70), 
				 origin: new google.maps.Point(0, 0), 
				 anchor: new google.maps.Point(20, 60) 
	};
    
	var myMarker = new google.maps.Marker({
		position: latlng,
		map: map,
		icon: image,
		clickable: true,
		title: "My Location"
	});
		
    myMarker.info = new google.maps.InfoWindow({
		content: "<b>RISTORANTE KOBE 'CEPPO'</b><br>Kanetsu-cho, Chuo-ku, Kobe,<br>Hyogo Prefecture 650-0001"
	});
		
    google.maps.event.addListener(
		myMarker, 'click', function () { 
			myMarker.info.open(map, myMarker); 
		});
	} 
	
	google.maps.event.addDomListener(window, 'load', initialize);