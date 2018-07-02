function getCounter()
{
    $.ajax({
        url: ajax_url + 'problemReports/counter',
        type: 'get',
        dataType: 'json',
        success: function(data) {

            if (data > 0)
            {
                $('.problem-report-label').html(data).show();
            }
            else
            {
                $('.problem-report-label').html('').hide();
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

function seenReport(report_id)
{
    $.ajax({
        url: ajax_url + 'problemReports/seen',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'report_id': report_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    location.href = ajax_url + 'problemReports/list';
                    break;
                case 0:
                    toastr.error(data.error);
                    break;
            }
        },
        error: function() {
            toastr.error(error);
        }
    });
}

$(document).ready(function() {

    getCounter();

    $('.seen-report').on('click', function() {

        var report_id = $(this).attr('data-report-id');

        seenReport(report_id);
    });
});