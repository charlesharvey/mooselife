window.onload = function(){

    'use strict';


    if (typeof google !== 'undefined') {

        var geocoder = new google.maps.Geocoder();

        // var acf_address = document.getElementById('acf-field_5c8a99e4be06c');
        // var acf_lat = document.getElementById('acf-field_5c0e7fcb96661');
        var acf_address = document.getElementById('acf-field_5c8d0162b545f');
        var acf_lat = document.getElementById('acf-field_5c26603eee560');

        if (acf_address) {
            acf_address.addEventListener("blur", function(e) {
                e.preventDefault();
                codeAddress( acf_address.value, acf_lat );

            });
        }



        function codeAddress( address, input ) {

            // location_results.innerHTML = '';
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == 'OK') {

                    console.log(results);

                    if (results.length > 0) {
                        var result = results[0];
                        var location = result.geometry.location
                        var lat = location.lat();
                        var lng = location.lng();
                        var latlng = lat + ', ' + lng;
                        input.value = latlng;
                    }


                } else {
                    input.value = 'Error';
                }
            });
        }


    }; // if have locations and google


    // code goes here
};
