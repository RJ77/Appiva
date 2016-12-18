<!DOCTYPE html>
<html>
  <head>
    <title>Place Autocomplete</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=yes">
    <meta charset="utf-8">
    <style>
      
      #map {
        height: 450px;
        width: 1080px;
    
      }
      
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
      
.modal {
    display: block; 
    
    z-index: 1; 
    padding-top: 20px; 
   padding-bottom: 20px
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
}


.modal-content {
    
    background-color: #fefefe;
    margin-left:60px;
    margin-bottom: 20px;
    padding: 0;
    border: 1px solid #888;
    width: 90%;
    height: 90%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 4s;
    animation-name: animatetop;
    animation-duration: 4s
}

@-webkit-keyframes animatetop {
    from {top:-900px; opacity:0} 
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-900px; opacity:0}
    to {top:0; opacity:1}
}


.close {
    color: white;
    float: right;
    font-size: 40px;
    font-weight: bold; 
    margin-top: 10px;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: #a8c9ff;
    color: white;
}

.modal-body {
  padding: 2px 16px;
   background-color:white;
    color: black;
}

.modal-footer {
    padding: 2px 16px;
    background-color: #a8c9ff;
    color: white;
}

    </style>
  </head>
  <body onload="clr()">
  <div>
  
     <div id='myModal' class='modal'>


            <div class='modal-content'>
                             <div class='modal-header'>
                                 <span onclick='cl()' class='close' style="border: 2px; border-color: black">OK</span>
                                      <h2>Search location and click OK</h2>
                                      <h4 style="color: red">Note : If map dosen't load propery Click <a href="javascript:;" onclick="ref()">here</a>!   (Click on location to select or Drag marker to position.)</h4></div>
                                               <div class='modal-body'>
                                                    <input id="pac-input" class="controls" type="text"
                                                        placeholder="Enter a location">
                                                    <div id="type-selector" class="controls">
                                                      <input type="radio" name="type" id="changetype-all" >
                                                      <label for="changetype-all">All</label>

                                                      <input type="radio" name="type" id="changetype-establishment">
                                                      <label for="changetype-establishment">Establishments</label>

                                                      <input type="radio" name="type" id="changetype-address">
                                                      <label for="changetype-address">Addresses</label>

                                                      <input type="radio" name="type" id="changetype-geocode" checked="checked">
                                                      <label for="changetype-geocode">Geocodes</label>
                                                    </div>
                                                    <div id="map" style="position: none"></div>
                                                        </div>
                                                     <div class='modal-footer'></div> 
                    </div>
            </div>
<div style="margin: 300px 200px 200px 400px">
  <input type="button" onclick="notifi()" id="btn" value="Show map">
  <input type="text" style="width: 400px" id="Location" placeholder="Choose location from map..." disabled="">
</div>
                                                       
    </div>                                             
   

    <script>
      
      function initMap() {
      var $Location= document.getElementById('Location');
            
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13
        });
         google.maps.event.trigger(map, 'resize');
        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('pac-input'));

        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29),
           draggable: true
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }

          
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17); 
          }
          marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
          }));
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          $Location.value="Latitude : " +place.geometry.location.lat() +"  Longitude : " +place.geometry.location.lng();

            google.maps.event.addListener(marker, 'dragend', function (marker) {
                var latLng = marker.latLng;
                $Location.value = "Latitude : " +latLng.lat() +"  Longitude : " +latLng.lng();
               
            });
        google.maps.event.addListener(marker, 'click', function (event) {
    $Location.value = "Latitude : " +this.getPosition().lat()+"  Longitude : " + this.getPosition().lng();
          });

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });

      
        
       map.addListener('click', function(e) {
        setTimeout(function(){marker.setPosition(e.latLng);},10);
      $Location.value = "Latitude : " +e.latLng.lat()+"  Longitude : " + e.latLng.lng();
      google.maps.event.addListener(marker, 'click', function (event) {
    $Location.value = "Latitude : " +this.getPosition().lat()+"  Longitude : " + this.getPosition().lng();
          });

  });


        marker.addListener('click', function() {
          
          map.setCenter(marker.getPosition());
        });

        
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
      }

    </script>
    <script type="text/javascript">

var modal = document.getElementById('myModal');

var span = document.getElementsByClassName("close")[0];


span.onclick = function() {
    modal.style.display = "none";
}


window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function cl(){

  

 modal.style.display="none";

}
 
function notifi(){
  
                     
  modal.style.display = "block";

  clr();

                  }

                  
function explode(){
  modal.style.display="none";
}
setTimeout(explode, 3000);

function ref(){

    location.reload();
}

function clr(){
  document.getElementById("Location").value= "";
  document.getElementById("pac-input").value= "";
}


</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPQ7PC_2JN_9jNw4z3N-PIsOtJF6HY-Hs&libraries=places&callback=initMap"
        async defer></script>
      
  </body>
</html>