$.getScript(ajax_url + 'js/functions/manufacturers.js?v=' + current_date_time);
$.getScript(ajax_url + 'js/functions/generalTypes.js?v=' + current_date_time);

function validateForm(is_insert)
{
    //set inputs array
    var inputs_array = ['code', 'manufacturer', 'name', 'model', 'picture', 'manufacture-year', 'serial-number', 'mass',
        'machine-type', 'pin', 'purchase-date', 'sale-date', 'start-working-hours', 'end-working-hours', 'register-date',
        'certificate-end-date'];

    var date_test = /^[0-9]{2}\.[0-9]{2}\.[0-9]{4}\.$/;
    var year_test = /^[0-9]{4}$/;
    var integer_test = /^[0-9]+$/;

    var check_validation = 1;

    //loop through all inputs and make validation
    $.each(inputs_array, function(index, value) {

        var current_input_string = '.' + value;
        var current_input = $(current_input_string);

        current_input.removeAttr('style');

        if (value == 'manufacturer' || value == 'machine-type')
        {
            if (current_input.has('option').length == 0)
            {
                $(current_input).css('border', '1px solid #FF0000');

                check_validation = 0;
            }
        }
        else if (value == 'picture')
        {
            if (is_insert == 'T')
            {
                if (current_input.get(0).files.length == 0)
                {
                    $(current_input).css('border', '1px solid #FF0000');

                    check_validation = 0;
                }
            }
        }
        else if (value == 'manufacture-year')
        {
            if (!year_test.test(current_input.val()))
            {
                $(current_input).css('border', '1px solid #FF0000');

                check_validation = 0;
            }
        }
        else if (value == 'code' || value == 'mass' || value == 'start-working-hours' || value == 'end-working-hours')
        {
            if (value == 'end-working-hours')
            {
                if (current_input.val().trim() != '')
                {
                    if (!integer_test.test(current_input.val()))
                    {
                        $(current_input).css('border', '1px solid #FF0000');

                        check_validation = 0;
                    }
                }
            }
            else
            {
                if (!integer_test.test(current_input.val()))
                {
                    $(current_input).css('border', '1px solid #FF0000');

                    check_validation = 0;
                }
            }
        }
        else if (value == 'purchase-date' || value == 'sale-date' || value == 'register-date' || value == 'certificate-end-date')
        {
            if (value == 'sale-date')
            {
                if (current_input.val().trim() != '')
                {
                    if (!date_test.test(current_input.val()))
                    {
                        $(current_input).css('border', '1px solid #FF0000');

                        check_validation = 0;
                    }
                }
            }
            else if (value == 'register-date')
            {
                if ($('.register-number').val().trim() != '')
                {
                    if (!date_test.test(current_input.val()))
                    {
                        $(current_input).css('border', '1px solid #FF0000');

                        check_validation = 0;
                    }
                }
            }
            else
            {
                if (!date_test.test(current_input.val()))
                {
                    $(current_input).css('border', '1px solid #FF0000');

                    check_validation = 0;
                }
            }
        }
        else
        {
            if (current_input.val().trim() == '')
            {
                $(current_input).css('border', '1px solid #FF0000');

                check_validation = 0;
            }
        }
    });

    return check_validation;
}

function insertMachine()
{
    $.ajax({
        url: ajax_url + 'resources/machines/insert',
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: new FormData($('.machine-form')[0]),
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'resources/machines/list';
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

function updateMachine()
{
    $.ajax({
        url: ajax_url + 'resources/machines/update',
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: new FormData($('.machine-form')[0]),
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'resources/machines/list';
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

$(document).ready(function() {

    $('.insert-machine').on('click', function() {

        var validation = validateForm('T');

        if (!validation)
        {
            toastr.error(validation_error);

            return 0;
        }

        insertMachine();
    });

    $('.update-machine').on('click', function() {

        var validation = validateForm('F');

        if (!validation)
        {
            toastr.error(validation_error);

            return 0;
        }

        updateMachine();
    });
});