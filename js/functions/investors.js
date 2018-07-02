function getInvestors()
{
    $.ajax({
        url: ajax_url + 'investors/list',
        type: 'get',
        dataType: 'json',
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    $('.investor').html('');

                    var investors_select = document.getElementsByClassName('investor');

                    $.each(data.data, function(index, value) {

                        var opt = document.createElement('option');
                        opt.innerHTML = value;
                        opt.value = index;
                        investors_select[0].appendChild(opt);
                    });

                    $('#addInvestorModal').modal('hide');

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

function insertInvestor(name, country, city_id, city, address)
{
    $.ajax({
        url: ajax_url + 'investors/insert',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'name': name, 'country': country, 'city_id': city_id, 'city': city, 'address': address},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    getInvestors();
                    toastr.success(investor_insert);
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

function manageInvestorCityInput(country)
{
    if (country == 1)
    {
        $('#investor-city-id-div').show();
        $('#investor-city-div').hide();
    }
    else
    {
        $('#investor-city-id-div').hide();
        $('#investor-city-div').show();
    }
}

$(document).ready(function() {

    $('.add-investor').click(function() {

        $('#addInvestorModal').modal('show');
    });

    $('.investor-country').on('change', function() {

        var country = $('.investor-country').val();

        manageInvestorCityInput(country);
    });

    $('.insert-investor').on('click', function() {

        var investor_name = $('.investor-name');
        var investor_country = $('.investor-country');
        var investor_city_id = $('.investor-city-id');
        var investor_city = $('.investor-city');
        var investor_address = $('.investor-address');

        investor_name.removeAttr('style');
        investor_city.removeAttr('style');
        investor_address.removeAttr('style');

        var check_validation = 1;

        if (investor_name.val().trim() == '')
        {
            investor_name.css('border', '1px solid #FF0000');

            check_validation = 0;
        }

        if (investor_country.val() != 1)
        {
            if (investor_city.val().trim() == '')
            {
                $(investor_city).css('border', '1px solid #FF0000');
            }
        }

        if (investor_address.val().trim() == '')
        {
            investor_address.css('border', '1px solid #FF0000');

            check_validation = 0;
        }

        if (!check_validation)
        {
            toastr.error(validation_error);

            return 0;
        }

        insertInvestor(investor_name.val(), investor_country.val(), investor_city_id.val(), investor_city.val(), investor_address.val());
    });
});