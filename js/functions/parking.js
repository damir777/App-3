function validateForm()
{
    //set inputs array
    var inputs_array = ['name', 'address'];

    var check_validation = 1;

    //loop through all inputs and make validation
    $.each(inputs_array, function(index, value) {

        var current_input_string = '.' + value;
        var current_input = $(current_input_string);

        current_input.removeAttr('style');

        if (current_input.val().trim() == '')
        {
            $(current_input).css('border', '1px solid #FF0000');

            check_validation = 0;
        }
    });

    return check_validation;
}

function insertParking()
{
    $.ajax({
        url: ajax_url + 'parking/insert',
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: new FormData($('.parking-form')[0]),
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'parking/list';
                    break;
                case 2:
                    toastr.error(validation_error);
                    break;
                case 3:
                    toastr.error(data.error);
                    break;
                case 0:
                    toastr.error(error);
                    break;
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

function updateParking()
{
    $.ajax({
        url: ajax_url + 'parking/update',
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: new FormData($('.parking-form')[0]),
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'parking/list';
                    break;
                case 2:
                    toastr.error(validation_error);
                    break;
                case 3:
                    toastr.error(data.error);
                    break;
                case 0:
                    toastr.error(error);
                    break;
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

function getLocation()
{
    var latitude = $("#latitude").val();
    var longitude = $("#longitude").val();

    if (latitude != 0 && longitude != 0)
    {
        initialize('T', latitude, longitude);
    }
    else
    {
        initialize('F');
    }
}

function initialize(location, lat, lon)
{
    var mapOptions;

    if (location === 'T')
    {
        mapOptions = {
            zoom: 11,
            center: new google.maps.LatLng(lat, lon)
        };

        $("#latitude").val(lat);
        $("#longitude").val(lon);
    }
    else
    {
        mapOptions = {
            zoom: 7,
            center: new google.maps.LatLng(44.75, 16.69)
        };
    }

    map = new google.maps.Map(document.getElementById('gMap'), mapOptions);

    if (location === 'T')
    {
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lon),
            map: map
        });

        active_markers.push(marker);
    }

    google.maps.event.addListener(map, 'click', function(e) {

        placeMarker(e.latLng);

        $("#latitude").val(e.latLng.lat());
        $("#longitude").val(e.latLng.lng());
    });
}

function placeMarker(position)
{
    for (j = 0; j < active_markers.length; j++)
    {
        active_markers[j].setMap(null);
    }

    marker = new google.maps.Marker({
        position: position,
        map: map
    });

    map.panTo(position);

    active_markers.push(marker);
}

var active_markers = [];
var map;

$(document).ready(function() {

    $('.insert-parking').on('click', function() {

        var validation = validateForm();

        if (!validation)
        {
            toastr.error(validation_error);

            return 0;
        }

        insertParking();
    });

    $('.update-parking').on('click', function() {

        var validation = validateForm();

        if (!validation)
        {
            toastr.error(validation_error);

            return 0;
        }

        updateParking();
    });

    $('#locationModal').on('shown.bs.modal', function () {

        setTimeout(function() {

            getLocation();
        }, 1000);
    });
});