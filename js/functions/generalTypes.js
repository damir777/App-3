function getGeneralTypes()
{
    $.ajax({
        url: ajax_url + 'generalTypes/list',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'type': general_type_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    $('.' + general_type_class).html('');

                    var types_select = document.getElementsByClassName(general_type_class);

                    $.each(data.data, function(index, value) {

                        var opt = document.createElement('option');
                        opt.innerHTML = value;
                        opt.value = index;
                        types_select[0].appendChild(opt);
                    });

                    $('#addGeneralTypeModal').modal('hide');

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

function insertGeneralType(name)
{
    $.ajax({
        url: ajax_url + 'generalTypes/insert',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'type': general_type_id, 'name': name},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    getGeneralTypes();
                    $('.type-name').val('');
                    toastr.success(insert_message);
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

var general_types_array = {'machine-type': 1, 'tool-type': 2, 'equipment-type': 3, 'vehicle-type': 4, 'fuel-type': 5, 'work-type': 6,
    'contract-type': 7};

var general_type_id = '';
var general_type_class = '';
var insert_message = '';

$(document).ready(function() {

    $('.add-general-type').click(function() {

        general_type_class = $(this).attr('data-type');
        general_type_id = general_types_array[general_type_class];
        insert_message = $(this).attr('data-insert-message');

        $('#addGeneralTypeModal').modal('show');
    });

    $('.insert-general-type').on('click', function() {

        var general_type_input = $('.type-name');

        var name = general_type_input.val().trim();

        if (name == '')
        {
            general_type_input.css('border', '1px solid #FF0000');

            toastr.error(validation_error);

            return 0;
        }

        insertGeneralType(name);
    });
});