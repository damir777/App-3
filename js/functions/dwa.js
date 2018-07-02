function getInitialData(dwa_id)
{
    $.ajax({
        url: ajax_url + 'DWA/initialData',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    site_id = data.site_id;
                    employee_id = data.employee_id;
                    work_type = data.work_type;
                    additional_sites = data.additional_sites;

                    fillActivitiesSelect(data.activity_types);
                    fillMachineComponentsSelect(data.fluid_components, data.filter_components);

                    if (!dwa_id)
                    {
                        if (additional_sites.length > 0)
                        {
                            fillSitesSelect(data.additional_sites);
                        }
                        else
                        {
                            fillMachinesSelect(data.machines);
                            fillToolsSelect(data.tools);

                            if (employee_id === 0)
                            {
                                fillEmployeesSelect(data.employees);
                            }
                        }
                    }
                    else
                    {
                        machine_id = data.dwa_machine_id;

                        if (work_type !== 5)
                        {
                            fillEmployeesSelect(data.employees);
                        }

                        fillToolsSelect(data.tools);
                        showFormInputs();
                    }

                    break;
                case 2:
                    toastr.warning(data.warning);
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

function fillSitesSelect(sites)
{
    var sites_select = $('.site');

    $.each(sites, function(index, value) {

        var opt = document.createElement('option');
        opt.innerHTML = value.name;
        opt.value = value.id;
        sites_select[0].appendChild(opt);
    });

    $('#sites-div').show();
}

function fillEmployeesSelect(employees)
{
    var employees_select = $('.employee');

    employees_select.html('');

    $.each(employees, function(index, value) {

        var opt = document.createElement('option');
        opt.innerHTML = value;
        opt.value = index;
        employees_select[0].appendChild(opt);
    });

    $('#employee-div').show();
}

function fillMachinesSelect(machines)
{
    var machines_select = $('.machine');

    machines_select.html('');

    $.each(machines, function(index, value) {

        var opt = document.createElement('option');
        opt.innerHTML = value;
        opt.value = index;
        machines_select[0].appendChild(opt);
    });

    $('#machine-div').show();
}

function fillActivitiesSelect(activities)
{
    var activities_select = $('.activity');

    activities_select.html('');

    $.each(activities, function(index, value) {

        var opt = document.createElement('option');
        opt.innerHTML = value;
        opt.value = index;
        activities_select[0].appendChild(opt);
    });
}

function fillToolsSelect(tools)
{
    var tools_select = $('.tool');

    tools_select.html('');

    $.each(tools, function(index, value) {

        var opt = document.createElement('option');
        opt.innerHTML = value;
        opt.value = index;
        tools_select[0].appendChild(opt);
    });
}

function fillMachineComponentsSelect(fluid_components, filter_components)
{
    var fluid_components_select = $('.fluid-component');
    var filter_components_select = $('.filter-component');

    $.each(fluid_components, function(index, value) {

        var opt = document.createElement('option');
        opt.innerHTML = value;
        opt.value = index;
        fluid_components_select[0].appendChild(opt);
    });

    $.each(filter_components, function(index, value) {

        var opt = document.createElement('option');
        opt.innerHTML = value;
        opt.value = index;
        filter_components_select[0].appendChild(opt);
    });
}

function checkMachineDWA(machine_id)
{
    $.ajax({
        url: ajax_url + 'DWA/checkMachineDWA',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'site_id': site_id, 'machine_id': machine_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    hideInitialMachineDiv();
                    showFormInputs();
                    dwa_id = data.dwa_id;
                    site_id = data.site_id;
                    break;
                case 2:
                    hideFormInputs();

                    if (work_type !== 2 && work_type !== 3)
                    {
                        showInitialMachineDiv();
                    }

                    toastr.info(data.info);
                    break;
                case 3:
                    toastr.warning(data.info);
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

function createDWA()
{
    if (employee_id === 0)
    {
        employee_id = $('.employee').val();
    }

    var machine_id = $('.machine').val();
    var damage_note = $('.damage-note').val();
    var machine_checked = 'F';
    var damage = 'F';

    if ($('.machine-checked').is(':checked'))
    {
        machine_checked = 'T';
    }

    if ($('.damage').is(':checked'))
    {
        damage = 'T';
    }

    $.ajax({
        url: ajax_url + 'DWA/createDWA',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'site_id': site_id, 'employee_id': employee_id, 'machine_id': machine_id, 'machine_checked': machine_checked, 'damage': damage,
            'damage_note': damage_note},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    hideInitialMachineDiv();
                    showFormInputs();
                    toastr.success(data.success);
                    dwa_id = data.dwa_id;
                    break;
                case 2:
                    hideInitialMachineDiv();
                    showFormInputs();
                    toastr.info(data.info);
                    dwa_id = data.dwa_id;
                    break;
                case 3:
                    toastr.warning(data.warning);
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

function showInitialMachineDiv()
{
    $('#initial-machine-check-div').show();
    $('.damage-note').val('');
}

function hideInitialMachineDiv()
{
    $('#initial-machine-check-div').hide();
    $('#initial-damage-note-div').hide();
    $('.damage-note').val('');
}

function showFormInputs()
{
    if (work_type === 1 || work_type === 4 || work_type === 5)
    {
        $('#activities-div').show();
        $('#fuel-div').show();
        $('#working-hours-div').show();
        $('#fluids-div').show();
        $('#filters').show();
        $('#notes-div').show();
    }
    else if (work_type === 2)
    {
        $('#fuel-div').show();
        $('#fluids-div').show();
        $('#filters').show();
        $('#notes-div').show();
    }
    else
    {
        $('#fuel-div').show();
        $('#fluids-div').show();
        $('#filters').show();
    }
}

function hideFormInputs()
{
    $('#activities-div').hide();
    $('#fuel-div').hide();
    $('#working-hours-div').hide();
    $('#fluids-div').hide();
    $('#filters').hide();
    $('#notes-div').hide();
}

function validateForm(inputs_array)
{
    var integer_test = /^[0-9]+$/;
    var time_test = /^([01]\d|2[0-3]):([0-5][0-9])$/;

    var check_validation = 1;

    //loop through all inputs and make validation
    $.each(inputs_array, function(index, value) {

        var current_input_string = '.' + value;
        var current_input = $(current_input_string);

        current_input.removeAttr('style');

        if (value === 'start-time' || value === 'end-time')
        {
            if (!time_test.test(current_input.val()))
            {
                $(current_input).css('border', '1px solid #FF0000');

                check_validation = 0;
            }
        }
        else if (value === 'fuel-quantity' || value === 'start-working-hours' || value === 'end-working-hours' || value === 'fluid-quantity' ||
            value === 'filter-quantity')
        {
            if (value === 'start-working-hours' || value === 'end-working-hours')
            {
                var activity = $('.activity').val();

                if (!integer_test.test(current_input.val()))
                {
                    $(current_input).css('border', '1px solid #FF0000');

                    check_validation = 0;
                }

                if (value === 'end-working-hours')
                {
                    if (activity != 66)
                    {
                        if (current_input.val() == $('.start-working-hours').val() || current_input.val() < $('.start-working-hours').val())
                        {
                            $(current_input).css('border', '1px solid #FF0000');

                            check_validation = 0;

                            toastr.warning(working_hours_error);
                        }
                    }
                    else
                    {
                        if (current_input.val() != $('.start-working-hours').val())
                        {
                            if (current_input.val() == $('.start-working-hours').val() || current_input.val() < $('.start-working-hours').val())
                            {
                                $(current_input).css('border', '1px solid #FF0000');

                                check_validation = 0;

                                toastr.warning(working_hours_error);
                            }
                        }
                    }
                }
            }
            else
            {
                if (!integer_test.test(current_input.val()) || current_input.val() < 1)
                {
                    $(current_input).css('border', '1px solid #FF0000');

                    check_validation = 0;
                }
            }
        }
        else
        {
            if (current_input.val().trim() === '')
            {
                $(current_input).css('border', '1px solid #FF0000');

                check_validation = 0;
            }
        }
    });

    return check_validation;
}

function saveActivity()
{
    if (employee_id === 0)
    {
        employee_id = $('.employee').val();
    }

    var start_time = $('.start-time').val();
    var end_time = $('.end-time').val();
    var tool_id = $('.tool').val();
    var activity = $('.activity').val();
    var start_working_hours = $('.start-working-hours').val();
    var end_working_hours = $('.end-working-hours').val();

    $.ajax({
        url: ajax_url + 'DWA/saveActivity',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'is_edit': is_edit, 'dwa_id': dwa_id, 'site_id': site_id, 'employee_id': employee_id, 'machine_id': machine_id,
            'start_time': start_time, 'end_time': end_time, 'tool_id': tool_id, 'activity': activity, 'start_working_hours': start_working_hours,
            'end_working_hours': end_working_hours},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    toastr.success(data.success);
                    getActivities();
                    break;
                case 2:
                    hideFormInputs();
                    toastr.info(data.info);
                    getActivities();
                    break;
                case 3:
                    toastr.warning(data.warning);
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

function saveFuel()
{
    if (employee_id === 0)
    {
        employee_id = $('.employee').val();
    }

    var quantity = $('.fuel-quantity').val();
    var invoice_number = $('.invoice-number').val();

    $.ajax({
        url: ajax_url + 'DWA/saveFuel',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'is_edit': is_edit, 'dwa_id': dwa_id, 'site_id': site_id, 'employee_id': employee_id, 'machine_id': machine_id,
            'quantity': quantity, 'invoice_number': invoice_number},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    toastr.success(data.success);
                    getFuel();
                    break;
                case 2:
                    hideFormInputs();
                    toastr.info(data.info);
                    getFuel();
                    break;
                case 3:
                    toastr.warning(data.warning);
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

function saveFluid()
{
    if (employee_id === 0)
    {
        employee_id = $('.employee').val();
    }

    var component = $('.fluid-component').val();
    var quantity = $('.fluid-quantity').val();

    $.ajax({
        url: ajax_url + 'DWA/saveFluid',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'is_edit': is_edit, 'dwa_id': dwa_id, 'site_id': site_id, 'employee_id': employee_id, 'machine_id': machine_id,
            'component': component, 'quantity': quantity},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    toastr.success(data.success);
                    getFluids();
                    break;
                case 2:
                    hideFormInputs();
                    toastr.info(data.info);
                    getFluids();
                    break;
                case 3:
                    toastr.warning(data.warning);
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

function saveFilter()
{
    if (employee_id === 0)
    {
        employee_id = $('.employee').val();
    }

    var component = $('.filter-component').val();
    var quantity = $('.filter-quantity').val();

    $.ajax({
        url: ajax_url + 'DWA/saveFilter',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'is_edit': is_edit, 'dwa_id': dwa_id, 'site_id': site_id, 'employee_id': employee_id, 'machine_id': machine_id,
            'component': component, 'quantity': quantity},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    toastr.success(data.success);
                    getFilters();
                    break;
                case 2:
                    hideFormInputs();
                    toastr.info(data.info);
                    getFilters();
                    break;
                case 3:
                    toastr.warning(data.warning);
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

function saveNote()
{
    if (employee_id === 0)
    {
        employee_id = $('.employee').val();
    }

    var note = $('.note').val();

    $.ajax({
        url: ajax_url + 'DWA/saveNote',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'is_edit': is_edit, 'dwa_id': dwa_id, 'site_id': site_id, 'employee_id': employee_id, 'machine_id': machine_id, 'note': note,
            'photo': null},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    toastr.success(data.success);
                    getNotes();
                    break;
                case 2:
                    hideFormInputs();
                    toastr.info(data.info);
                    getNotes();
                    break;
                case 3:
                    toastr.warning(data.warning);
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

function getActivities()
{
    $('#activities-history').fadeOut();

    $.ajax({
        url: ajax_url + 'DWA/getActivities',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    var activities_string = '';
                    var activity_string = '';
                    var activities_counter = 0;

                    $.each(data.activities, function(index, value) {

                        activity_string = value.activity;

                        if (value.tool)
                        {
                            activity_string += ' (' + value.tool + ')';
                        }

                        activities_string += '<div class="feed-element"><div class="media-body">' +
                            '<small class="pull-right">' + value.hours + '</small><strong>' + value.employee + '</strong><br>' +
                            '<small class="text-muted">' + value.start_hour + ' - ' + value.end_hour + '<br>'+ activity_string + '<br>' +
                            start_working_hours_trans + ': ' + value.start_working_hours + '<br>' + end_working_hours_trans + ': ' +
                            value.end_working_hours + '</small>';

                        if (is_edit === 'T' && work_type === 4 || work_type === 5)
                        {
                            activities_string += '<button type="button" class="btn btn-primary btn-sm delete-dwa-input delete-activity"' +
                                ' data-activity-id="' + value.id + '"><i class="fa fa-times" aria-hidden="true"></i></button>';
                        }

                        activities_string += '</div></div>';

                        activities_counter++;
                    });

                    $('.feed-activity-list').html('').html(activities_string);

                    if (activities_counter > 0)
                    {
                        $('#hours-sum').html(data.hours_sum);
                        $('#activities-history').fadeIn();
                    }

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

function getFuel()
{
    $('#fuel-history').fadeOut();

    $.ajax({
        url: ajax_url + 'DWA/getFuel',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    var fuel_string = '';
                    var fuel_counter = 0;

                    var media_body_class = 'media-body';

                    if (is_edit === 'T')
                    {
                        media_body_class = 'media-body-edit';
                    }

                    $.each(data.fuel, function(index, value) {

                        fuel_string += '<div class="feed-element"><div class="' + media_body_class + '">' +
                            '<small class="pull-right">' + value.quantity + '</small><strong>' + value.employee + '</strong>';

                        if (value.invoice_number)
                        {
                            fuel_string += '<br><small class="text-muted">' + value.invoice_number + '</small>';
                        }

                        if (is_edit === 'T' && work_type === 4 || work_type === 5)
                        {
                            fuel_string += '<button type="button" class="btn btn-primary btn-sm delete-dwa-input delete-fuel"' +
                                ' data-fuel-id="' + value.id + '"><i class="fa fa-times" aria-hidden="true"></i></button>';
                        }

                        fuel_string += '</div></div>';

                        fuel_counter++;
                    });

                    $('.feed-activity-list').html('').html(fuel_string);

                    if (fuel_counter > 0)
                    {
                        $('#fuel-sum').html(data.fuel_sum);
                        $('#fuel-history').fadeIn();
                    }

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

function getFluids()
{
    $('#fluids-history').fadeOut();

    $.ajax({
        url: ajax_url + 'DWA/getFluids',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    var fluids_string = '';
                    var fluids_counter = 0;

                    var media_body_class = 'media-body';

                    if (is_edit === 'T')
                    {
                        media_body_class = 'media-body-edit';
                    }

                    $.each(data.fluids, function(index, value) {

                        fluids_string += '<div class="feed-element"><div class="' + media_body_class + '">' +
                            '<small class="pull-right">' + value.quantity + '</small><strong>' + value.employee + '</strong><br>' +
                            '<small class="text-muted">' + value.component + '</small>';

                        if (is_edit === 'T' && work_type === 4 || work_type === 5)
                        {
                            fluids_string += '<button type="button" class="btn btn-primary btn-sm delete-dwa-input delete-fluid"' +
                                ' data-fluid-id="' + value.id + '"><i class="fa fa-times" aria-hidden="true"></i></button>';
                        }

                        fluids_string += '</div></div>';

                        fluids_counter++;
                    });

                    $('.feed-activity-list').html('').html(fluids_string);

                    if (fluids_counter > 0)
                    {
                        $('#fluids-sum').html(data.fluids_sum);
                        $('#fluids-history').fadeIn();
                    }

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

function getFilters()
{
    $('#filters-history').fadeOut();

    $.ajax({
        url: ajax_url + 'DWA/getFilters',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    var filters_string = '';
                    var filters_counter = 0;

                    var media_body_class = 'media-body';

                    if (is_edit === 'T')
                    {
                        media_body_class = 'media-body-edit';
                    }

                    $.each(data.filters, function(index, value) {

                        filters_string += '<div class="feed-element"><div class="' + media_body_class + '">' +
                            '<small class="pull-right">' + value.quantity + '</small><strong>' + value.employee + '</strong><br>' +
                            '<small class="text-muted">' + value.component + '</small>';

                        if (is_edit === 'T' && work_type === 4 || work_type === 5)
                        {
                            filters_string += '<button type="button" class="btn btn-primary btn-sm delete-dwa-input delete-filter"' +
                                ' data-filter-id="' + value.id + '"><i class="fa fa-times" aria-hidden="true"></i></button>';
                        }

                        filters_string += '</div></div>';

                        filters_counter++;
                    });

                    $('.feed-activity-list').html('').html(filters_string);

                    if (filters_counter > 0)
                    {
                        $('#filters-sum').html(data.filters_sum);
                        $('#filters-history').fadeIn();
                    }

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

function getNotes()
{
    $('#notes-history').fadeOut();

    $.ajax({
        url: ajax_url + 'DWA/getNotes',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    var notes_string = '';
                    var photo_string = '';

                    $.each(data.notes, function(index, value) {

                        photo_string = '';

                        if (value.photo)
                        {
                            photo_string = '<br><a href="' + value.photo + '" data-gallery=""><img src="' + value.photo +
                                '" style="width: 100px"></a>';
                        }

                        notes_string += '<div class="feed-element"><div class="media-body "><strong>' + value.note + '</strong>' + photo_string +
                            '</div></div>';
                    });

                    $('.notes-list').html('').html(notes_string);

                    $('#notes-history').fadeIn();

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

function deleteActivity(activity_id)
{
    $.ajax({
        url: ajax_url + 'DWA/deleteActivity',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id, 'activity_id': activity_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    getActivities();
                    break;
                case 2:
                    toastr.warning(data.warning);
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

function deleteFuel(fuel_id)
{
    $.ajax({
        url: ajax_url + 'DWA/deleteFuel',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id, 'fuel_id': fuel_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    getFuel();
                    break;
                case 2:
                    toastr.warning(data.warning);
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

function deleteFluid(fluid_id)
{
    $.ajax({
        url: ajax_url + 'DWA/deleteFluid',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id, 'fluid_id': fluid_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    getFluids();
                    break;
                case 2:
                    toastr.warning(data.warning);
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

function deleteFilter(filter_id)
{
    $.ajax({
        url: ajax_url + 'DWA/deleteFilter',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'dwa_id': dwa_id, 'filter_id': filter_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:
                    getFilters();
                    break;
                case 2:
                    toastr.warning(data.warning);
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

var site_id = 0;
var additional_sites = {};
var employee_id = 0;
var work_type = 1;
var machine_id = 0;
var dwa_id = 0;
var is_edit = 'F';

$(document).ready(function() {

    dwa_id = edit_dwa_id;
    machine_id = edit_machine_id;

    if (dwa_id)
    {
        is_edit = 'T';
    }

    getInitialData(dwa_id);

    $('.site').on('change', function() {

        site_id = $('.site').val();
        employee_id = 0;

        if (site_id != 0)
        {
            $.each(additional_sites, function(index, value) {

                if (value.id == site_id)
                {
                    fillEmployeesSelect(value.employees);
                    fillMachinesSelect(value.machines);
                    fillToolsSelect(value.tools);
                }
            });

            $('#employee-div').show();
            $('#machine-div').show();
        }
        else
        {
            $('#employee-div').hide();
            $('#machine-div').hide();
        }

        hideInitialMachineDiv();
        hideFormInputs();
    });

    $('.machine').on('change', function() {

        var select_machine_id = $('.machine').val();

        if (select_machine_id != 0)
        {
            machine_id = select_machine_id;

            checkMachineDWA(machine_id);
        }
        else
        {
            hideInitialMachineDiv();
            hideFormInputs();
        }
    });

    $('.employee').on('change', function() {

        employee_id = $('.employee').val();
    });

    $('.create-dwa').on('click', function() {

        createDWA();
    });

    $('.save-activity').on('click', function() {

        var validation = validateForm(['start-time', 'end-time', 'start-working-hours', 'end-working-hours']);

        if (!validation)
        {
            toastr.error(validation_error);
            return 0;
        }

        saveActivity();
    });

    $('.save-fuel').on('click', function() {

        var validation = validateForm(['fuel-quantity']);

        if (!validation)
        {
            toastr.error(validation_error);
            return 0;
        }

        saveFuel();
    });

    $('.save-fluid').on('click', function() {

        var validation = validateForm(['fluid-quantity']);

        if (!validation)
        {
            toastr.error(validation_error);
            return 0;
        }

        saveFluid();
    });

    $('.save-filter').on('click', function() {

        var validation = validateForm(['filter-quantity']);

        if (!validation)
        {
            toastr.error(validation_error);
            return 0;
        }

        saveFilter();
    });

    $('.save-note').on('click', function() {

        var validation = validateForm(['note']);

        if (!validation)
        {
            toastr.error(validation_error);
            return 0;
        }

        saveNote();
    });

    $('.get-activities').on('click', function() {

        getActivities();
    });

    $('.get-fuel').on('click', function() {

        getFuel();
    });

    $('.get-fluids').on('click', function() {

        getFluids();
    });

    $('.get-filters').on('click', function() {

        getFilters();
    });

    $('.get-notes').on('click', function() {

        getNotes();
    });

    $('.damage').on('ifChanged', function() {

        if ($(this).is(':checked'))
        {
            $('#initial-damage-note-div').show();
        }
        else
        {
            $('#initial-damage-note-div').hide();
        }
    });

    $('.feed-activity-list').on('click', '.delete-activity', function() {

        var activity_id = $(this).attr('data-activity-id');

        deleteActivity(activity_id);
    });

    $('.feed-activity-list').on('click', '.delete-fuel', function() {

        var fuel_id = $(this).attr('data-fuel-id');

        deleteFuel(fuel_id);
    });

    $('.feed-activity-list').on('click', '.delete-fluid', function() {

        var fluid_id = $(this).attr('data-fluid-id');

        deleteFluid(fluid_id);
    });

    $('.feed-activity-list').on('click', '.delete-filter', function() {

        var filter_id = $(this).attr('data-filter-id');

        deleteFilter(filter_id);
    });
});