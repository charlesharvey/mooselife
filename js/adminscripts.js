window.onload = function(){

    'use strict';




    if (typeof google !== 'undefined') {

        var geocoder = new google.maps.Geocoder();

        var location_search = document.getElementById('location_search');
        var location_search_button = document.getElementById('location_search_button');
        var location_results = document.getElementById('location_results');

        if (location_search_button) {
            location_search_button.addEventListener("click", function(e) {
                e.preventDefault();
                codeAddress( location_search.value );

            });
        }




        function codeAddress( address ) {

            location_results.innerHTML = '';
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == 'OK') {

                    console.log(results);

                    for (var r = 0; r < results.length; r++) {
                        var result = results[r];
                        var location = result.geometry.location
                        var lat = location.lat();
                        var lng = location.lng();
                        var latlng = lat + ', ' + lng;
                        var node =  document.createElement("LI");
                        var textnode = document.createTextNode( result.formatted_address + ' : ' +  latlng );
                        node.appendChild(textnode);
                        location_results.appendChild(node);
                    }


                } else {
                    var node =  document.createElement("LI");
                    var textnode = document.createTextNode( 'Error' );
                    node.appendChild(textnode);
                    location_results.appendChild(node);
                    console.log('Geocode was not successful for the following reason: ' + status);
                }
            });
        }


    }; // if have locations and google


    // code goes here
};
