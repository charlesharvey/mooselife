window.onload = function(){

    'use strict';


    // lAZY LOAD GALLERY IMAGES
    lazyload();




    if (typeof locations !== 'undefined' && typeof google !== 'undefined') {

        // console.table(locations);


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
        // map_container.css({
        //     width : '100%',
        //     height: 560
        // })




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
                repeat: '240px'
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
                                     infowindow.setContent( marker.title );
                                     infowindow.open(map, marker);
                                     // map.setCenter( marker.position );
                                 }
                             }

                        }


                })
            })(op);
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
