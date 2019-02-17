window.onload = function(){

    'use strict';


    // lAZY LOAD GALLERY IMAGES
    lazyload();




    if (typeof locations !== 'undefined' && typeof google !== 'undefined') {

        // console.table(locations);
        google.maps.LatLng.prototype.kmTo = function(a){
            var e = Math, ra = e.PI/180;
            var b = this.lat() * ra, c = a.lat() * ra, d = b - c;
            var g = this.lng() * ra - a.lng() * ra;
            var f = 2 * e.asin(e.sqrt(e.pow(e.sin(d/2), 2) + e.cos(b) * e.cos
            (c) * e.pow(e.sin(g/2), 2)));
            return f * 6378.137;
        }
        google.maps.Polyline.prototype.inKm = function(n){
            var a = this.getPath(n), len = a.getLength(), dist = 0;
            for (var i=0; i < len-1; i++) {
               dist += a.getAt(i).kmTo(a.getAt(i+1));
            }
            return dist;
        }

        var map_theme=[{"featureType": "administrative","elementType": "labels.text.fill","stylers": [{"color": "#444444"}]},{"featureType": "landscape","elementType": "all","stylers": [{"color": "#f2f2f2"}]},{"featureType": "poi","elementType": "all","stylers": [{"visibility": "off"}]},{"featureType": "road","elementType": "all","stylers": [{"saturation": -100},{"lightness": 45}]},{"featureType": "road.highway","elementType": "all","stylers": [{"visibility": "simplified"}]},{"featureType": "road.arterial","elementType": "labels.icon","stylers": [{"visibility": "off"}]},{"featureType": "transit","elementType": "all","stylers": [{"visibility": "off"}]},{"featureType": "water","elementType": "all","stylers": [{"color": "#46bcec"},{"visibility": "on"}]}];


        var map_options = {
            zoom: 11,
            mapTypeControl: true,
            scrollwheel: true,
            draggable: true,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: map_theme
        };



        var map_container =  document.getElementById('map_container');





        var map = new google.maps.Map( map_container , map_options);
        var bounds = new google.maps.LatLngBounds();
        var infowindow = new google.maps.InfoWindow({content: '...'});
        var markers = [];

        for (var  i = 0;  i < locations.length ;i++) {
            var location = locations[i];
            if (location != null) {
                addPointToMap(map, location, bounds, infowindow, markers);
            }

        }
        var lineSymbol = {
            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
        };
        var flightPath = new google.maps.Polyline({
            path: locations,
            icons: [{
                icon: lineSymbol,
                offset: '0',
                repeat: '200px'
            }],
            map: map,
            geodesic: true,
            strokeColor: '#FF3322',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });




        if (locations.length > 1) {
            map.setZoom(12);
            map.setCenter( locations[ locations.length - 1] );
        } else {
            map.initialZoom = true;
            map.fitBounds(bounds);
        }



        var $open_markers =  document.getElementsByClassName('open_marker');
        for(var op = 0; op < $open_markers.length; op++) {
            (function(index) {
                $open_markers[index].addEventListener("click", function(e) {
                    e.preventDefault();
                    var $this = $open_markers[index];
                    var $location_id = $this.dataset.id;
                    if ($location_id) {
                        for (var i = 0; i < markers.length; i++) {
                            var marker = markers[i];
                            if (marker.id == $location_id) {
                                openMarker(marker);
                            }
                        }

                    }


                })
            })(op);
        };

        function openMarker(marker) {
            infowindow.setContent( marker.title );
            infowindow.open(map, marker);

            // map.setCenter( marker.position );
        }


        var see_all =  document.getElementById('see_all');
        see_all.addEventListener("click", function(e) {
            e.preventDefault();
            if (bounds) {
                map.fitBounds(bounds);
            }
        });
        var see_latest =  document.getElementById('see_latest');
        see_latest.addEventListener("click", function(e) {
            e.preventDefault();
            if (markers) {
                var marker = markers[markers.length -1];
                openMarker(marker);
                map.setZoom(12);

            }
        });

        var total_distance =  document.getElementById('total_distance');
        var total_kms =  flightPath.inKm();
        if (total_kms) {
            total_distance.innerHTML = '~' + Math.round(total_kms) + ' KM ';
        }








    }; // if have locations and google


    // code goes here
};






function addPointToMap(map,  location, bounds, infowindow, markers ) {
    var latitude = location.lat;
    var longitude = location.lng;
    var latlng = new google.maps.LatLng(  latitude , longitude);
    var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        title: location.title,
        id: location.id,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 7,
            fillColor: "#FF3322",
            strokeColor: '#FF3322',
            fillOpacity: 0.8,
            strokeWeight: 1


        }
    });

    marker.addListener('click', function() {
        infowindow.setContent( this.title );
        // +  '<br><a href="#l='+ this.id + '">See photos</a>'
        var box = document.getElementById('location_' + this.id);
        window.scrollTo( {top:   box.offsetTop  - 20 , behavior: 'smooth'  });

        infowindow.open(map, this);
    });

    markers.push(marker);

    bounds.extend(latlng);


};
