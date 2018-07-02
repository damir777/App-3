function getStatistic(work_type)
{
    $.ajax({
        url: ajax_url + 'filterStatistic',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'work_type': work_type},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    $('#active-employees').html(data.data.active_employees);
                    $('#fixed-term-contract').html(data.data.fixed_term_contract);
                    $('#indefinite-contract').html(data.data.indefinite_contract);
                    $('#men').html(data.data.men);
                    $('#women').html(data.data.women);
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

    $('.work-type').on('change', function() {

        var work_type = $('.work-type').val();

        getStatistic(work_type);
    });

    $('.make-pdf').on('click', function() {

        var resource_type = $('.type').val();
        var search_string = $('.search_string').val();
        var search_filter = $('.search_filter').val();

        location.href = ajax_url + 'overview/pdf/' + resource_type + '?search_string=' + search_string + '&search_filter=' + search_filter;
    });
});