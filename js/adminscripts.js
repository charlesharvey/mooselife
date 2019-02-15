window.onload = function(){

    'use strict';




    if (typeof google !== 'undefined') {

        var geocoder = new google.maps.Geocoder();

        var location_search = document.getElementById('location_search');
        var location_search_button = document.getElementById('location_search_button');
        var location_result = document.getElementById('location_result');
        location_search_button.addEventListener("click", function(e) {
            e.preventDefault();
            codeAddress( location_search.value );

        });


        function codeAddress( address ) {
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == 'OK') {
                    var location = results[0].geometry.location
                    var lat = location.lat();
                    var lng = location.lng();
                    location_result.innerHTML = lat + ', ' + lng;
                } else {
                    location_result.innerHTML = 'Error';
                    console.log('Geocode was not successful for the following reason: ' + status);
                }
            });
        }


    }; // if have locations and google


    // code goes here
};
