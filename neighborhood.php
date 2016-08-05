<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Reverse Geocoding</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #text {
        height: 50%;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
      }
      .text-container {
        position: absolute;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        text-align: center;
        padding: 30px;
        height: 230px;
        width: 100%;
        box-sizing: border-box;
      }
      h1 {
          font-family: 'Satisfy', cursive;
      }
      h2 {
          font-family: 'Playfair Display', serif;
      }
      #map {
        height: 50%;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
      }
    </style>
    <link href='https://fonts.googleapis.com/css?family=Satisfy|Playfair+Display' rel='stylesheet' type='text/css'>
  </head>
  <body>
      <div id="text">
          <div class="text-container">
              <h1>What neighborhood am I in?</h1>
              <div class="text-response">
                  <h2></h2>
              </div>
          </div>
      </div>
    <div id="map"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: {lat: 40.731, lng: -73.997}
        });
        var geocoder = new google.maps.Geocoder;
        var infoWindow = new google.maps.InfoWindow;

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            geocoder.geocode({'location': pos}, function(results, status) {
              if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                  map.setZoom(11);
                  var marker = new google.maps.Marker({
                    position: pos,
                    map: map
                  });

                  jQuery.each(results[1]['address_components'], function(i) {
                      jQuery.each(results[1]['address_components'][i]['types'], function(j) {
                          if (results[1]['address_components'][i]['types'][j] == 'neighborhood') {
                              jQuery('.text-response h2').text(results[1]['address_components'][i]['long_name']);
                          }
                      });
                  });


                } else {
                  jQuery('.text-response h2').text("We can't figure it out.");
                }
              } else {
                jQuery('.text-response h2').text("Your browser won't let us find you. Try Google Chrome!");
              }
            });

            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
          jQuery('.text-response h2').text("Your browser won't let us find you. Try Google Chrome!");
        }
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
      }

    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=[YOUR API KEY]&callback=initMap">
    </script>
  </body>
</html>
