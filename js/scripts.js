window.onload = function(){

    'use strict';


    // lAZY LOAD GALLERY IMAGES
    lazyload();






    if (typeof locations !== 'undefined' && typeof google !== 'undefined') {


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


        var colors = [
            '#e74c3c',
            '#e67e22',
            '#f1c40f',
            '#27ae60',
            '#2980b9',
            '#9b59b6',
        ]


        var lineSymbol = {
            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
        };
        var km_travelled = 0;


        var map = new google.maps.Map( map_container , map_options);
        var bounds = new google.maps.LatLngBounds();
        var infowindow = new google.maps.InfoWindow({content: '...'});
        var markers = [];

        for (var  i = 0;  i < locations.length ;i++) {

            var location = locations[i];
            var color =  colors[i % colors.length];
            if (location != null) {
                addPointToMap(map, location, bounds, infowindow, markers, color);
            }

            if (i < locations.length - 1) {
                color =  colors[ (i  + 1) % colors.length];
                var b_location = locations[i +1 ];
                var path_locations = [ location, b_location ];
                var flightPath = new google.maps.Polyline({
                    path: path_locations,
                    icons: [{
                        icon: lineSymbol,
                        offset: '0',
                        repeat: '200px'
                    }],
                    map: map,
                    geodesic: true,
                    strokeColor:  color ,
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });

                km_travelled +=  distanceCalc( location, b_location );
            }

        }




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
        if (km_travelled) {
            total_distance.innerHTML = '~' + Math.round(km_travelled) + ' KM ';
        }








    }; // if have locations and google


    // code goes here
};




function distanceCalc(a,b) {
    var e = Math, ra = e.PI/180;
    var alat = a.lat * ra, blat = b.lat * ra, d = alat - blat;
    var g = a.lng * ra - b.lng * ra;
    var f = 2 * e.asin(e.sqrt(e.pow(e.sin(d/2), 2) + e.cos(alat) * e.cos
    (blat) * e.pow(e.sin(g/2), 2)));
    return f * 6378.137;
}



function addPointToMap(map,  location, bounds, infowindow, markers, color ) {
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
            fillColor: color,
            strokeColor: color,
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
