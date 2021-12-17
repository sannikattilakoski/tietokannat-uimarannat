<?php
    //open connection to mysql db
    $connection = mysqli_connect("localhost","root","","uimapaikka_db") or die("Error " . mysqli_error($connection));

	$sql = 	"SELECT * from kartta
				ORDER BY Keskiarvo DESC";
    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

    //create an array
    $emparray = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
    
	//write to json file
    $fp = fopen('rantadata.json', 'w');
    fwrite($fp, json_encode($emparray));
    fclose($fp);
	

    //close the db connection
    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <title>Keski-Suomen viihtyisimmät uimarannat</title>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css"
   integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
   crossorigin=""/>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amatic+SC&display=swap" rel="stylesheet">

 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.3.3/dist/leaflet.js"
   integrity="sha512-tAGcCfR4Sc5ZP5ZoVz0quoZDYX5aCtEm/eu1KhSLj2c9eFrylXZknQYmxUssFaVJKvvc0dJQixhGjG2yXWiV9Q=="
   crossorigin=""></script>


<style type="text/css">
.container {
  position: relative;
  text-align: center;
  color: white;
  margin-bottom: 20px;
}

.bottom-left {
  position: absolute;
  bottom: 80px;
  left: 30px;
  font-size: 36px;
  font-family: "Amatic SC", cursive;
  color: blue;
  font-weight: bold;
}

.center {
  position: absolute;
  top: 20%;
  width: 100%;
  text-align: center;
  font-size: 80px;
  font-family: "Amatic SC", cursive;
  font-weight: bold;
}


#mapid { height: 600px; width: 500px; margin-left: 20px; margin-bottom: 20px; float: left;} 

.custom-popup .leaflet-popup-content-wrapper {
  background:white;
  font-size:12px;
  line-height:18px;
  position: relative;
  top: -30px
  }
.custom-popup .leaflet-popup-content-wrapper a {
  color:rgba(0,0,255,0.5);
  }
.custom-popup .leaflet-popup-tip-container {
  width:30px;
  height:15px;
  }
.custom-popup .leaflet-popup-tip {
  border-left:15px solid transparent;
  border-right:15px solid transparent;
  border-top:15px solid #2c3e50;
  }
.teksti { 	
	font-size: 48px;
	font-family: "Amatic SC", cursive;
	color: blue;
	float: left;
	height: 500px; 
	width:900px; 
	background-color:white;
	margin-top:10px;
	padding:10px;
	text-align: center;
	font-weight: bold;
}
p { margin: 10px 0;}

.button {
	background-color: #6658FF;
	color: white;
	font-family: "Amatic SC", cursive;
	font-size: 24px;
}
.pin {
	height: 20px; 
	width:20px;
}

</style>

   </head>

   <body>

	  
<div class="container">
  <img src="suokki2.jpg" alt="ranta" style="height: 700px;width:100%">
  <div class="bottom-left">
	<div>Kävitkö uimarannalla?</div>
	<div>Tallenna kokemuksesi ja osallistu <br> arvontaan</div>
	<button class='button' onclick="window.open('https://student.labranet.jamk.fi/~AB5148/tietokannat_harkat/Tietokannat_harkkatyo/kysely.html')">Lue lisää</button>
  </div>
  <div class="center">Keski-Suomen viihtyisimmät uimarannat</div>
</div>

<div class='custom-popup' id="mapid"></div>
<div class='teksti' id="tekstilaatikko">
	<p>Tässä ovat Keski-Suomen viihtyisimmät uimarannat</p>
	<p>Neulan väri kuvastaa annettuja arvosteluja:</p>
	<img class="pin" src="green_pin.png"</img><span style="font-size:25px" >   Keskiarvo 4.5 tai yli</span><br>
	<img class="pin" src="blue_pin.png"</img><span style="font-size:25px" >   Keskiarvo 3.5 tai yli</span><br>
	<img class="pin" src="gold_pin.png"</img><span style="font-size:25px" >   Keskiarvo 2.5 tai yli</span><br>
	<img class="pin" src="red_pin.png"</img><span style="font-size:25px" >   Keskiarvo alle 2.5</span>
	<br></br>
	<p>Valitse kartalta nähdäksesi lisätietoja</p>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script>
var mymap = L.map('mapid').setView([62.5644298, 25.8671195], 8);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
tileSize: 512,
maxZoom: 18,
zoomOffset: -1,
id: 'mapbox/streets-v11',
accessToken: 'pk.eyJ1IjoiaGVubmFrYXJpIiwiYSI6ImNrcWtnbmtrOTA4d2Myd3BtZWs5ejNpa3cifQ._fIyiQB2rPRJbCRAx8jbDA'
}).addTo(mymap);

var yellowIcon = L.icon({
    iconUrl: 'gold_pin.png', 
    iconSize:     [25, 25], // size
    iconAnchor:   [16, 25], // point of the icon which will correspond to marker's location
    popupAnchor:  [-20, -20] // point from which the popup should open relative to the iconAnchor
});

var greenIcon = L.icon({
    iconUrl: 'green_pin.png', 
    iconSize:     [25, 25], // size
    iconAnchor:   [16, 25], // point of the icon which will correspond to marker's location
    popupAnchor:  [-20, -20] // point from which the popup should open relative to the iconAnchor
});

var blueIcon = L.icon({
    iconUrl: 'blue_pin.png', 
    iconSize:     [25, 25], // size
    iconAnchor:   [16, 25], // point of the icon which will correspond to marker's location
    popupAnchor:  [-20, -20] // point from which the popup should open relative to the iconAnchor
});

var redIcon = L.icon({
    iconUrl: 'red_pin.png', 
    iconSize:     [25, 25], // size
    iconAnchor:   [16, 25], // point of the icon which will correspond to marker's location
    popupAnchor:  [-20, -20] // point from which the popup should open relative to the iconAnchor
});


$(document).ready(function(){
	$.ajax({
		url: 'rantadata.json'
	}).fail(function() {
        console.log("fail!");
	}).done(function(data) {
		// loop through all courses
		$.each(data, function(index, ranta) {
		// marker, get position lat and lng
		
		if (ranta.Keskiarvo >= 4.5 ) { 
			var marker = L.marker([ranta.KoordinaatitLat, ranta.KoordinaatitLng], {icon: greenIcon}).addTo(mymap);
		} else if (ranta.Keskiarvo >= 3.5 ) {
			var marker = L.marker([ranta.KoordinaatitLat, ranta.KoordinaatitLng], {icon: blueIcon}).addTo(mymap);
		} else if (ranta.Keskiarvo >= 2.5 )  {
			var marker = L.marker([ranta.KoordinaatitLat, ranta.KoordinaatitLng], {icon: yellowIcon}).addTo(mymap);
		} else {
			var marker = L.marker([ranta.KoordinaatitLat, ranta.KoordinaatitLng], {icon: redIcon}).addTo(mymap);
		}
		//var marker = L.marker([ranta.KoordinaatitLat, ranta.KoordinaatitLng], {icon: yellowIcon}).addTo(mymap);
		var popup = L.popup();

		function onMapClick(e) {
			popup
				.setLatLng(e.latlng)
				.setContent("<h2>"+ranta.UimapaikkaNimi+"</h2>"+ranta.Paikkakunta+"<br>"+ranta.Kuvaus+"<br>"+"<br>"+"Annettujen arvosanojen keskiarvo:"+ranta.Keskiarvo)
				.openOn(mymap);
		}

		marker.on('click', onMapClick);
		});
	}).always(function() {
		console.log("complete");
	});
}); 


</script>


   </body>
   
</html>
