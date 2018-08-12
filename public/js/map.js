$(document).ready(function(){

    $('#get_cities').on('click', function(){
        get_cities();
    });

    $('#city_name').on('keyup', function(){
        var $name = $(this).val();
        if($name.length > 2){
            getcitiesNameOption($name);
        }
    });

    $('#city_name').on('focus', function(){
        if($('.names_option>li').length > 0){
            $('.names_option').slideDown();
        }
    });

    $('#city_name').on('focusout', function(){
        $('.names_option').slideUp();
    });

    $(document).on("click",".names_option>li",function() {
        $(this).parent().slideUp();
        $('#city_name').val($(this).text());
    });

    initDefaultMap();
});

function getcitiesNameOption($name){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var $this = $('#city_name');
    var $name = $this.val();
    var $container = $('.names_option');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/getcitiesnameoption',
        data: 'name='+$name,
        beforeSend: function(){
            $container.empty();
            $container.append('<img src="public/images/loading.gif">');
        },
        success: function(data) {
            $("#get_cities").prop('disabled', false);
            $container.empty();
            $container.slideDown();
            var $li = '';
            if(data.length == 0){
                $container.empty();
                $container.append('<li>Not Result Found</li>');
                $("#get_cities").prop('disabled', true);
            }
            else{
                data.forEach(function(element) {
                    $li = '<li>'+element['alternate_name']+'</li>';
                    $container.append($li);
                });
            }

        },
        error: function (error){
            $container.empty();
            $container.append('<li>Not Result Found</li>');
            $("#get_cities").prop('disabled', true);
        }
    });
}
function get_cities(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var $name = $('#city_name').val();
    var $count = $('#city_count').val();
    var $lang = $('#city_lang').val();

    var sendKeyVals = { name : $name, count : $count, lang : $lang }
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/getcities',
        data: sendKeyVals,
        success: function(data) {
            if(data[0] == false){
                alert('Result not found');
            }
            else{
                createMapInSelectedCities(data);
            }
        },

    });
}

function createMapInSelectedCities($cities){

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng($cities[0].latitude, $cities[0].longitude),
        mapTypeId: 'roadmap'
    });

    var $features = [];
    var $city = {};
    $cities.forEach(function(element){
        $city = {
                    position: new google.maps.LatLng(element.latitude, element.longitude),
                    type: 'info'
                };
        $features.push($city);
    });

    $features.forEach(function(feature) {
        var marker = new google.maps.Marker({
            position: feature.position,
            map: map
        });
    });

}

// Adds a marker to the map.
var map, infoWindow;
function initDefaultMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.397, lng: 150.644},
        zoom: 6
    });
    infoWindow = new google.maps.InfoWindow;

    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Location found.');
            infoWindow.open(map);
            map.setCenter(pos);
        }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}

function addMarker(location, map) {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: {lat: -33, lng: 151}
    });

    var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
    var beachMarker = new google.maps.Marker({
        position: {lat: -33.890, lng: 151.274},
        map: map,
        icon: image
    });
}