(function ($, root, undefined) {

    $(function () {

        'use strict';


        // lAZY LOAD GALLERY IMAGES
        $(".gallery img.lazyload").lazyload({
            load : function(elements_left, settings) {
            //    console.log(elements_left, settings);
            }
        });




        if (typeof locations !== 'undefined' && typeof google !== 'undefined') {

            // console.table(locations);


            var map_theme=[{"featureType": "administrative","elementType": "labels.text.fill","stylers": [{"color": "#444444"}]},{"featureType": "landscape","elementType": "all","stylers": [{"color": "#f2f2f2"}]},{"featureType": "poi","elementType": "all","stylers": [{"visibility": "off"}]},{"featureType": "road","elementType": "all","stylers": [{"saturation": -100},{"lightness": 45}]},{"featureType": "road.highway","elementType": "all","stylers": [{"visibility": "simplified"}]},{"featureType": "road.arterial","elementType": "labels.icon","stylers": [{"visibility": "off"}]},{"featureType": "transit","elementType": "all","stylers": [{"visibility": "off"}]},{"featureType": "water","elementType": "all","stylers": [{"color": "#46bcec"},{"visibility": "on"}]}];


            var map_options = {
                zoom: 13,
                mapTypeControl: true,
                scrollwheel: true,
                draggable: true,
                navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: map_theme
            };


            var map_container = $('#map_container');
            // map_container.css({
            //     width : '100%',
            //     height: 560
            // })




            var map = new google.maps.Map(map_container.get(0), map_options);
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
                    repeat: '40px'
                }],
                map: map,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });



            if (locations.length > 1) {
                map.setZoom(8);
                map.setCenter( locations[ locations.length - 1] );
            } else {
                map.initialZoom = true;
                map.fitBounds(bounds);
            }




            $('.open_marker').on('click', function(e) {
                e.preventDefault();
                var $this = $(this);
                var $location_id = $this.data('id');
                for (var i = 0; i < markers.length; i++) {
                    var marker = markers[i];
                    if (marker.id == $location_id) {
                        infowindow.setContent( marker.title );
                        infowindow.open(map, marker);
                        // map.setCenter( marker.position );
                    }
                }
            });


        }; // if have locations and google









        function addPointToMap(map,  location, bounds, infowindow, markers ) {
            var latitude = location.lat;
            var longitude = location.lng;
            var latlng = new google.maps.LatLng(  latitude , longitude);
            var marker = new google.maps.Marker({
                map: map,
                position: latlng,
                title: location.title,
                id: location.id
            });

            marker.addListener('click', function() {
                infowindow.setContent( this.title );
                // +  '<br><a href="#l='+ this.id + '">See photos</a>'
                infowindow.open(map, this);
            });

            markers.push(marker);

            bounds.extend(latlng);


        };




    });

})(jQuery, this);
