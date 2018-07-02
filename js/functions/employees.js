$.getScript(ajax_url + 'js/functions/generalTypes.js?v=' + current_date_time);

function validateForm(is_insert)
{
    //set inputs array
    var inputs_array = ['code', 'name', 'work-type', 'picture', 'oib', 'birth-date', 'birth-city', 'address', 'phone', 'contract-start-date',
        'contract-expire-date', 'medical-certificate-expire-date', 'contract-start-date'];

    var date_test = /^[0-9]{2}\.[0-9]{2}\.[0-9]{4}\.$/;
    var integer_test = /^[0-9]+$/;
    var oib_test = /^[0-9]{11}$/;

    var check_validation = 1;

    //loop through all inputs and make validation
    $.each(inputs_array, function(index, value) {

        var current_input_string = '.' + value;
        var current_input = $(current_input_string);

        current_input.removeAttr('style');

        if (value == 'work-type')
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
        else if (value == 'code')
        {
            if (!integer_test.test(current_input.val()))
            {
                $(current_input).css('border', '1px solid #FF0000');

                check_validation = 0;
            }
        }
        else if (value == 'oib')
        {
            if (!oib_test.test(current_input.val()))
            {
                $(current_input).css('border', '1px solid #FF0000');

                check_validation = 0;
            }
        }
        else if (value == 'birth-date' || value == 'contract-start-date' || value == 'contract-expire-date' || value == 'contract-end-date' ||
            value == 'medical-certificate-expire-date')
        {
            if (value == 'contract-end-date' || value == 'contract-expire-date' || value == 'medical-certificate-expire-date')
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

    var country = $('.country');
    var city = $('.city');

    city.removeAttr('style');

    if (country.val() != 1)
    {
        if (city.val().trim() == '')
        {
            $(city).css('border', '1px solid #FF0000');
        }
    }

    var account_validation = 1;

    if (is_insert == 'T')
    {
        if ($('.create-account').is(':checked'))
        {
            account_validation = validateAccount('T', 'T');

            if (!account_validation)
            {
                check_validation = 0;
            }
        }
    }
    else
    {
        if (has_account == 'T')
        {
            if ($('.password').val().trim() != '')
            {
                account_validation = validateAccount('T', 'T');

                if (!account_validation)
                {
                    check_validation = 0;
                }
            }
            else
            {
                account_validation = validateAccount('T', 'F');

                if (!account_validation)
                {
                    check_validation = 0;
                }
            }
        }
        else
        {
            if ($('.create-account').is(':checked'))
            {
                account_validation = validateAccount('T', 'T');

                if (!account_validation)
                {
                    check_validation = 0;
                }
            }
        }
    }

    return check_validation;
}

function validateAccount(required_email, required_password)
{
    var email = $('.email');
    var password = $('.password');
    var password_confirmation = $('.password-confirmation');

    email.removeAttr('style');
    password.removeAttr('style');
    password_confirmation.removeAttr('style');

    var email_test = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    var check_account = 1;

    if (required_email == 'T')
    {
        if (!email_test.test(email.val()))
        {
            $(email).css('border', '1px solid #FF0000');

            check_account = 0;
        }
    }

    if (required_password == 'T')
    {
        if (password.val().trim() == '')
        {
            $(password).css('border', '1px solid #FF0000');

            check_account = 0;
        }

        if (password_confirmation.val() != password.val())
        {
            $(password_confirmation).css('border', '1px solid #FF0000');

            check_account = 0;
        }
    }

    return check_account;
}

function insertEmployee()
{
    $.ajax({
        url: ajax_url + 'resources/employees/insert',
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: new FormData($('.employee-form')[0]),
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'resources/employees/list';
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

function updateEmployee()
{
    $.ajax({
        url: ajax_url + 'resources/employees/update',
        type: 'post',
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: new FormData($('.employee-form')[0]),
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'resources/employees/list';
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

function manageCityInput(country)
{
    if (country == 1)
    {
        $('#city-id-div').show();
        $('#city-div').hide();
    }
    else
    {
        $('#city-id-div').hide();
        $('#city-div').show();
    }
}

$(document).ready(function() {

    if ($('#employee_id').length)
    {
        var country = $('.country').val();

        manageCityInput(country);
    }

    $('.country').on('change', function() {

        var country = $('.country').val();

        manageCityInput(country);
    });

    var checkbox_input = $('input');

    checkbox_input.on('ifChecked', function(event) {

        $('.create-account-hidden').val('T');
    });

    checkbox_input.on('ifUnchecked', function(event) {

        $('.create-account-hidden').val('F');
    });

    $('.insert-employee').on('click', function() {

        var validation = validateForm('T');

        if (!validation)
        {
            toastr.error(validation_error);

            return 0;
        }

        insertEmployee();
    });

    $('.update-employee').on('click', function() {

        var validation = validateForm('F');

        if (!validation)
        {
            toastr.error(validation_error);

            return 0;
        }

        updateEmployee();
    });
});