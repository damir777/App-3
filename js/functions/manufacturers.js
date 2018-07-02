function getManufacturers()
{
    $.ajax({
        url: ajax_url + 'manufacturers/list',
        type: 'get',
        dataType: 'json',
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    $('.manufacturer').html('');

                    var manufacturers_select = document.getElementsByClassName('manufacturer');

                    $.each(data.data, function(index, value) {

                        var opt = document.createElement('option');
                        opt.innerHTML = value;
                        opt.value = index;
                        manufacturers_select[0].appendChild(opt);
                    });

                    $('#addManufacturerModal').modal('hide');

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

function insertManufacturer(name)
{
    $.ajax({
        url: ajax_url + 'manufacturers/insert',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'name': name},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    getManufacturers();
                    toastr.success(manufacturer_insert);
                    break;
                case 2:
                    toastr.error(validation_error);
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

$(document).ready(function() {

    $('.add-manufacturer').click(function() {

        $('#addManufacturerModal').modal('show');
    });

    $('.insert-manufacturer').on('click', function() {

        var manufacturer_input = $('.manufacturer-name');

        var name = manufacturer_input.val().trim();

        if (name == '')
        {
            manufacturer_input.css('border', '1px solid #FF0000');

            toastr.error(validation_error);

            return 0;
        }

        insertManufacturer(name);
    });
});