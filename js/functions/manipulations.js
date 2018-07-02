function refreshDashboard(refresh_type, refresh_sites, site_type, refresh_resources, employee_type, resource_type, list_type, current_page,
    search_string, search_filter, refresh_filter, site_id, parking_id, message)
{
    showSpinner(refresh_type);

    $.ajax({
        url: ajax_url + 'manipulations/dashboardData',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'get_sites': refresh_sites, 'get_resources': refresh_resources, 'employee_type': employee_type, 'resource_type': resource_type,
            'list_type': list_type, 'page': current_page, 'search_string': search_string, 'search_filter': search_filter,  'site_id': site_id,
            'parking_id': parking_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    setTimeout(function() {

                        if (refresh_sites === 'T')
                        {
                            appendSitesData(site_type, data.sites, data.parking);
                        }

                        if (refresh_resources === 'T')
                        {
                            if (!list_type || list_type === 'employees')
                            {
                                appendEmployeesData(data.employees, employee_type, search_string);
                            }

                            if (!list_type || list_type === 'active' || list_type === 'site')
                            {
                                appendResourcesData(data.resources, resource_type, list_type, search_string, search_filter, refresh_filter,
                                    site_id, parking_id, data.resources.resources_counter);
                            }
                        }

                        hideSpinner(refresh_type);

                        if (message)
                        {
                            toastr.success(message);
                        }
                    }, 400);

                    break;
                case 0:
                    hideSpinner(refresh_type);
                    toastr.error(error);
                    break;
            }
        },
        error: function() {
            hideSpinner(refresh_type);
            toastr.error(error);
        }
    });
}

function appendSitesData(site_type, sites, parking)
{
    var site_append_string = '';
    var parking_append_string = '';

    $.each(sites, function(index, value) {

        site_append_string += '<div class="col-lg-3"><div class="widget-head-color-box navy-bg p-lg text-center">' +
            '<div class="m-b-md mb-0">' +
            '<div class="project-title-holder">' +
            '<div class="project-title-fader">' +
            '</div>' +
            '<h2 class="font-bold no-margins">' + value.name + '</h2>' +
            '</div>' +
            '<small>' + value.address + ', ' + value.city + '</small>' +
            '<small>' + value.investor + '</small>' +
            '<div class="row dates-info">' +
            '<div class="col-xs-12">' +
            '<div class="row text-left">' +
            '<div class="col-xs-12">' +
            '<h5>' + value.start_date_trans + ': ' + value.start_date + '</h5>' +
            '</div>' +
            '<div class="col-xs-12">' +
            '<h5>' + value.plan_end_date_trans + ': ' + value.plan_end_date + '</h5>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="widget-text-box widget-icons text-center">' +
            '<div class="info-icon show-site-details" data-site-id="' + value.id + '" data-resource-type="1"' +
            ' data-site-name="' + value.name + '" data-resource-name="' + machines_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + machines_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/machine.svg"><h5>' + value.machines + '</h5></div>' +
            '<div class="info-icon show-site-details" data-site-id="' + value.id + '" data-resource-type="2"' +
            ' data-site-name="' + value.name + '" data-resource-name="' + tools_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + tools_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/tool.svg"><h5>' + value.tools + '</h5></div>' +
            '<div class="info-icon show-site-details" data-site-id="' + value.id + '" data-resource-type="3"' +
            ' data-site-name="' + value.name + '" data-resource-name="' + equipment_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + equipment_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/equipment.svg"><h5>' + value.equipment + '</h5></div>' +
            '<div class="info-icon show-site-details" data-site-id="' + value.id + '" data-resource-type="4"' +
            ' data-site-name="' + value.name + '" data-resource-name="' + vehicles_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + vehicles_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/vehicle.svg"><h5>' + value.vehicles + '</h5></div>' +
            '<div class="info-icon show-site-details" data-site-id="' + value.id + '" data-resource-type="5"' +
            ' data-site-name="' + value.name + '" data-resource-name="' + employees_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + employees_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/employee.svg"><h5>' + value.employees + '</h5></div>' +
            '</div>' +
            '</div>';

        if (value.latitude != 0 && value.longitude != 0)
        {
            var location = [value.name, value.city, value.address, value.latitude, value.longitude];
            site_locations.push(location);
        }
    });

    $.each(parking, function(index, value) {

        parking_append_string += '<div class="col-lg-3"><div class="widget-head-color-box navy-bg p-lg text-center">' +
            '<div class="m-b-md mb-0">' +
            '<div class="project-title-holder">' +
            '<div class="project-title-fader">' +
            '</div>' +
            '<h2 class="font-bold no-margins">' + value.name + '</h2>' +
            '</div>' +
            '<small>' + value.address + '</small>' +
            '</div>' +
            '</div>' +
            '<div class="widget-text-box text-center">' +
            '<div class="info-icon show-parking-details" data-parking-id="' + value.id + '" data-resource-type="1"' +
            ' data-parking-name="' + value.name + '" data-resource-name="' + machines_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + machines_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/machine.svg"><h5>' + value.machines + '</h5></div>' +
            '<div class="info-icon show-parking-details" data-parking-id="' + value.id + '" data-resource-type="2"' +
            ' data-parking-name="' + value.name + '" data-resource-name="' + tools_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + tools_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/tool.svg"><h5>' + value.tools + '</h5></div>' +
            '<div class="info-icon show-parking-details" data-parking-id="' + value.id + '" data-resource-type="3"' +
            ' data-parking-name="' + value.name + '" data-resource-name="' + equipment_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + equipment_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/equipment.svg"><h5>' + value.equipment + '</h5></div>' +
            '<div class="info-icon show-parking-details" data-parking-id="' + value.id + '" data-resource-type="4"' +
            ' data-parking-name="' + value.name + '" data-resource-name="' + vehicles_trans + '" data-toggle="tooltip"' +
            ' data-placement="top" data-original-title="' + vehicles_trans + '">' +
            '<img src="' + ajax_url + 'icons/resources/vehicle.svg"><h5>' + value.vehicles + '</h5></div>' +
            '</div>' +
            '</div>';

        if (value.latitude != 0 && value.longitude != 0)
        {
            var location = [value.name, null, value.address, value.latitude, value.longitude];
            site_locations.push(location);
        }
    });

    var sites_div = $('#sites-list');

    sites_div.html('');

    if (site_type === 'site')
    {
        sites_div.append(site_append_string);
    }
    else
    {
        sites_div.append(parking_append_string);
    }

    $('#sites-map').hide();
    sites_div.show();
}

function appendEmployeesData(employees, employee_type, search_string)
{
    $('#is-modal').val('F');

    var header_append_string = '';
    var body_append_string = '';

    var search_form_div = $('#employees-search-form');
    var list_data_div = $('#employees-list-data');
    var pagination_div = $('#employees-list-pagination');
    var no_data_div = $('#employees-no-data');

    var header_div = $('#employees-table-header');
    var body_div = $('#employees-table-body');

    search_form_div.hide();
    list_data_div.hide();
    pagination_div.hide();
    no_data_div.hide();

    if (employees.employees.length > 0)
    {
        $.each(employees.table_header, function(index, header) {

            header_append_string += '<th class="footable-visible">' + header + '</th>';
        });

        $.each(employees.employees, function(index, employee) {

            body_append_string += '<tr style="display: table-row;">' +
                '<td class="footable-visible"><img alt="image" src="' + employee.picture + '"></td>' +
                '<td class="footable-visible text-row">' + employee.code + '</td>' +
                '<td class="footable-visible text-row"><a href="' + employee.route + '">' + employee.name + '</a></td>' +
                '<td class="footable-visible text-row">' + employee.work_type + '</td>' +
                '<td class="footable-visible text-row">' + employee.oib + '</td>';

            body_append_string += '<td class="footable-visible button-row"><div class="btn-group">' +
                '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"' +
                ' aria-expanded="false">' + site_trans + ' <i class="fa fa-angle-right" aria-hidden="true"></i></button>' +
                '<ul class="dropdown-menu">';

            $.each(employees.sites, function(index, site) {

                body_append_string += '<li class="dropdown-site-item" data-manipulation-type="site" data-site-id="' + site.id + '"' +
                    ' data-resource-type="5" data-resource-id="' + employee.id + '">' + site.name + '</li>';
            });

            body_append_string += '</ul></div></td>';

            body_append_string += '<td class="footable-visible button-row"><div class="btn-group">' +
                '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"' +
                ' aria-expanded="false">' + status_trans + ' <i class="fa fa-angle-right" aria-hidden="true"></i></button>' +
                '<ul class="dropdown-menu status-dropdown">';

            $.each(employees.status_types, function(index, type) {

                body_append_string += '<li class="dropdown-status-item" data-manipulation-type="status" data-status-id="' + type.id + '"' +
                    ' data-resource-type="5" data-resource-id="' + employee.id + '">' + type.name + '</li>';
            });

            body_append_string += '</ul></div></td></tr>';
        });

        $('#employees-employee-type').val(employee_type);
        $('#employees-current-page').val(employees.page);

        if (employees.paginate === 'T')
        {
            var previous_pagination = $('.employees-previous-pagination');
            var next_pagination = $('.employees-next-pagination');

            var previous_parent = previous_pagination.parent();
            var next_parent = next_pagination.parent();

            previous_parent.removeClass('disabled');
            next_parent.removeClass('disabled');

            if (employees.previous_pagination === 'F')
            {
                previous_parent.addClass('disabled');
            }

            if (employees.next_pagination === 'F')
            {
                next_parent.addClass('disabled');
            }

            pagination_div.show();
        }
        else
        {
            pagination_div.hide();
        }
    }
    else
    {
        if (search_string !== '')
        {
            search_form_div.show();
        }
    }

    if (body_append_string !== '')
    {
        search_form_div.show();
        list_data_div.show();
    }
    else
    {
        list_data_div.hide();
        no_data_div.show();
    }

    header_div.html('');
    body_div.html('');

    header_div.append(header_append_string);
    body_div.append(body_append_string);
}

function appendResourcesData(resources, resource_type, list_type, search_string, search_filter, refresh_filter, site_id, parking_id,
    resources_counter)
{
    $('#is-modal').val('F');

    if (refresh_filter === 'T' && (site_id || parking_id))
    {
        var filter_select = $('#site-list-search-filter');

        filter_select.html('');

        $.each(resources.filter_options, function(index, value) {

            var opt = document.createElement('option');
            opt.innerHTML = value;
            opt.value = index;
            filter_select.append(opt);
        });
    }

    var list_type_prefix = 'active';

    if (list_type === 'site')
    {
        $('#is-modal').val('T');

        list_type_prefix = 'site';
    }

    var header_append_string = '';
    var body_append_string = '';

    var search_form_div = $('#' + list_type_prefix + '-search-form');
    var list_data_div = $('#' + list_type_prefix + '-list-data');
    var pagination_div = $('#' + list_type_prefix + '-list-pagination');
    var no_data_div = $('#' + list_type_prefix + '-no-data');

    var header_div = $('#' + list_type_prefix + '-table-header');
    var body_div = $('#' + list_type_prefix + '-table-body');

    search_form_div.hide();
    list_data_div.hide();
    pagination_div.hide();
    no_data_div.hide();

    if (resources.resources.length > 0)
    {
        $.each(resources.table_header, function(index, header) {

            header_append_string += '<th class="footable-visible">' + header + '</th>';
        });

        $.each(resources.resources, function(index, resource) {

            body_append_string += '<tr style="display: table-row;">' +
                '<td class="footable-visible"><img alt="image" src="' + resource.picture + '"></td>' +
                '<td class="footable-visible text-row">' + resource.code + '</td>';

            if (resource_type != 5)
            {
                body_append_string += '<td class="footable-visible text-row">' + resource.manufacturer + '</td>' +
                    '<td class="footable-visible text-row"><a href="' + resource.route + '">' + resource.name + '</a></td>' +
                    '<td class="footable-visible text-row">' + resource.model + '</td>';
            }
            else
            {
                body_append_string += '<td class="footable-visible text-row"><a href="' + resource.route + '">' + resource.name + '</a></td>' +
                    '<td class="footable-visible text-row">' + resource.work_type + '</td>' +
                    '<td class="footable-visible text-row">' + resource.oib + '</td>';
            }

            body_append_string += '<td class="footable-visible button-row"><div class="btn-group">' +
                '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"' +
                ' aria-expanded="false">' + site_trans + ' <i class="fa fa-angle-right" aria-hidden="true"></i></button>';

            if (site_id && resource_type == 5 && resource.additional_sites)
            {
                body_append_string += '<ul class="dropdown-menu multiple-sites-dropdown">';
            }
            else
            {
                body_append_string += '<ul class="dropdown-menu">';
            }

            $.each(resources.sites, function(index, site) {

                if (site_id && resource_type == 5 && resource.additional_sites)
                {
                    var icon_class = 'fa fa-plus';
                    var dropdown_class = 'dropdown-site-item';

                    $.each(resource.additional_sites, function(index, additional_site) {

                        if (site.id === additional_site.site_id)
                        {
                            icon_class = 'fa fa-minus';
                            dropdown_class = 'dropdown-site-item remove';
                        }
                    });

                    body_append_string += '<li><span class="site-name dropdown-site-item" data-manipulation-type="site"' +
                        ' data-site-id="' + site.id + '" data-resource-type="5" data-resource-id="' + resource.id + '">' + site.name +
                        '</span><span class="site-icon ' + dropdown_class + '" data-manipulation-type="additional" data-site-id="' + site.id +
                        '" data-resource-type="5" data-resource-id="' + resource.id + '"><i class="' + icon_class + '" aria-hidden="true"' +
                        ' id=""></i></span></li>';
                }
                else
                {
                    body_append_string += '<li class="dropdown-site-item" data-manipulation-type="site" data-site-id="' + site.id + '"' +
                        ' data-resource-type="' + resource_type + '" data-resource-id="' + resource.id + '">' + site.name + '</li>';
                }
            });

            body_append_string += '</ul></div></td>';

            if (resource_type != 5)
            {
                body_append_string += '<td class="footable-visible button-row"><div class="btn-group">' +
                    '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"' +
                    ' aria-expanded="false">' + parking_trans + ' <i class="fa fa-angle-right" aria-hidden="true"></i></button>' +
                    '<ul class="dropdown-menu parking-dropdown">';

                $.each(resources.parking, function(index, parking) {

                    body_append_string += '<li class="dropdown-parking-item" data-manipulation-type="parking" data-parking-id="' + parking.id +
                        '" data-resource-type="' + resource_type + '" data-resource-id="' + resource.id + '">' + parking.name + '</li>';
                });

                body_append_string += '</ul></div></td>';
            }
            else
            {
                body_append_string += '<td class="footable-visible button-row"><div class="btn-group">' +
                    '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"' +
                    ' aria-expanded="false">' + status_trans + ' <i class="fa fa-angle-right" aria-hidden="true"></i></button>' +
                    '<ul class="dropdown-menu status-dropdown">';

                $.each(resources.status_types, function(index, type) {

                    body_append_string += '<li class="dropdown-status-item" data-manipulation-type="status" data-status-id="' + type.id +
                        '" data-resource-type="5" data-resource-id="' + resource.id + '">' + type.name + '</li>';
                });

                body_append_string += '</ul></div></td>';
            }

            body_append_string += '</tr>';
        });

        $('#' + list_type_prefix + '-resource-type').val(resource_type);
        $('#' + list_type_prefix + '-current-page').val(resources.page);

        if (site_id || parking_id)
        {
            $('#resource-counter').html(' (' + resources_counter + ')');

            $('#resourcesModal').modal('show');
        }

        if (resources.paginate === 'T')
        {
            var previous_pagination = $('.' + list_type_prefix + '-previous-pagination');
            var next_pagination = $('.' + list_type_prefix + '-next-pagination');

            var previous_parent = previous_pagination.parent();
            var next_parent = next_pagination.parent();

            previous_parent.removeClass('disabled');
            next_parent.removeClass('disabled');

            if (resources.previous_pagination === 'F')
            {
                previous_parent.addClass('disabled');
            }

            if (resources.next_pagination === 'F')
            {
                next_parent.addClass('disabled');
            }

            pagination_div.show();
        }
        else
        {
            pagination_div.hide();
        }
    }
    else
    {
        if (search_string !== '' || search_filter !== 0)
        {
            search_form_div.show();
        }
    }

    if (body_append_string !== '')
    {
        search_form_div.show();
        list_data_div.show();
    }
    else
    {
        list_data_div.hide();
        no_data_div.show();
    }

    header_div.html('');
    body_div.html('');

    header_div.append(header_append_string);
    body_div.append(body_append_string);
}

function showMap()
{
    var mapOptions = {
        zoom: 7,
        center: new google.maps.LatLng(44.75, 16.69)
    };

    map = new google.maps.Map(document.getElementById("gMap"), mapOptions);

    var info_window = new google.maps.InfoWindow();

    for (var i = 0; i < site_locations.length; i++)
    {
        var icon = null;

        if (site_locations[i][1])
        {
            icon = ajax_url + 'icons/bulldozer.png';
        }
        else
        {
            icon = ajax_url + 'icons/parkinggarage.png';
        }

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(site_locations[i][3], site_locations[i][4]),
            icon: icon,
            map: map
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {

                if (site_locations[i][1])
                {
                    info_window.setContent('<h5>' + site_locations[i][0] + '</h5>' + site_locations[i][1] + '<br>' + site_locations[i][2]);
                }
                else
                {
                    info_window.setContent('<h5>' + site_locations[i][0] + '</h5>' + site_locations[i][2]);
                }

                info_window.open(map, marker);
            }
        })(marker, i));
    }
}

function showSpinner(class_prefix)
{
    $('#' + class_prefix + '-loader-div').addClass('sk-loading');
}

function hideSpinner(class_prefix)
{
    $('#' + class_prefix + '-loader-div').removeClass('sk-loading');
}

function getSpinnerClass()
{
    var spinner_class = 'dashboard';

    var is_modal = $('#is-modal').val();

    if (is_modal === 'T')
    {
        spinner_class = 'modal';
    }

    return spinner_class;
}

function doManipulation(refresh_type, manipulation_type, location_id, resource_type, resource_id, this_site)
{
    $.ajax({
        url: ajax_url + 'manipulations/doManipulation',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'manipulation_type': manipulation_type, 'location_id': location_id, 'resource_type': resource_type, 'resource_id': resource_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    var resource_type = $('#active-resource-type').val();
                    var employee_type = $('#employees-employee-type').val();

                    if (refresh_type === 'dashboard')
                    {
                        $('#employees-list-search').val('');
                        $('#active-list-search').val('');
                    }
                    else
                    {
                        $('#site-list-search-string').val('');
                    }

                    if (manipulation_type !== 'additional')
                    {
                        $('#resourcesModal').modal('hide');

                        sitesSetActiveTab();

                        refreshDashboard('dashboard', 'T', 'site', 'T', employee_type, resource_type, null, 1, '', 0, 'F', null,
                            null, data.message);
                    }
                    else
                    {
                        hideSpinner('modal');

                        this_site.addClass('remove');
                        this_site.find('i').removeClass('fa-plus').addClass('fa-minus');

                        toastr.success(data.message);
                    }

                    break;
                case 2:
                    hideSpinner(refresh_type);
                    toastr.error(data.error);
                    break;
                case 0:
                    hideSpinner(refresh_type);
                    toastr.error(error);
                    break;
            }
        },
        error: function() {
            hideSpinner(refresh_type);
            toastr.error(error);
        }
    });
}

function removeAdditionalSite(site_id, employee_id, this_site)
{
    $.ajax({
        url: ajax_url + 'manipulations/removeAdditionalSite',
        type: 'post',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); },
        data: {'site_id': site_id, 'employee_id': employee_id},
        success: function(data) {

            var responseStatus = data.status;

            switch (responseStatus)
            {
                case 1:

                    hideSpinner('modal');

                    this_site.removeClass('remove');
                    this_site.find('i').removeClass('fa-minus').addClass('fa-plus');

                    toastr.success(data.message);

                    break;
                case 2:
                    hideSpinner('modal');
                    toastr.error(data.error);
                    break;
                case 0:
                    hideSpinner('modal');
                    toastr.error(error);
                    break;
            }
        },
        error: function() {
            hideSpinner('modal');
            toastr.error(error);
        }
    });
}

function sitesSetActiveTab()
{
    var counter = 1;

    $('.sites-list-item').each(function() {

        if (counter === 1)
        {
            $(this).addClass('active');
        }
        else
        {
            $(this).removeClass('active');
        }

        counter++;
    });

    $('.sites-map-item').removeClass('active');
}

var map;
var site_locations = [];

$(document).ready(function() {

    refreshDashboard('dashboard', 'T', 'site', 'T', 1, 1, null, 1, '', 0, 'F', null, null, null);

    $('.sites-list-item').on('click', function(e) {

        e.preventDefault();

        var this_item = $(this);

        if (!this_item.hasClass('active'))
        {
            $('.sites-list-item').each(function() {

                $(this).removeClass('active');
            });

            $('.sites-map-item').removeClass('active');

            this_item.addClass('active');

            var site_type = this_item.attr('data-site-type');

            refreshDashboard('dashboard', 'T', site_type, 'F', null, null, null, null, null, null, null, null, null, null);
        }
    });

    $('.sites-map-item').on('click', function(e) {

        e.preventDefault();

        var this_tem = $(this);

        if (!this_tem.hasClass('active'))
        {
            $('.sites-list-item').removeClass('active');

            this_tem.addClass('active');

            $('#sites-list').hide();
            $('#gMap').html('');
            $('#sites-map').show(function() {

                showMap();
            });
        }
    });

    /*********************************************************************************/

    $('.employees-item').on('click', function(e) {

        e.preventDefault();

        var this_tem = $(this);

        if (!this_tem.hasClass('active'))
        {
            $('.employees-item').each(function() {

                $(this).removeClass('active');
            });

            this_tem.addClass('active');

            var employee_type = this_tem.attr('data-employee-type');

            $('#employees-employee-type').val(employee_type);
            $('#employees-list-search').val('');

            refreshDashboard('dashboard', 'F', null, 'T', employee_type, 5, 'employees', 1, '', 0, 'F', null, null, null);
        }
    });

    $('.employees-previous-pagination').on('click', function(e) {

        e.preventDefault();

        var parent = $(this).parent();

        if (!parent.hasClass('disabled'))
        {
            var current_page_input = $('#employees-current-page');

            var current_page = parseInt(current_page_input.val()) - 1;
            var employee_type = $('#employees-employee-type').val();
            var search_string = $.trim($('#employees-list-search').val());

            current_page_input.val(current_page);

            refreshDashboard('dashboard', 'F', null, 'T', employee_type, 5, 'employees', current_page, search_string, 0, 'F', null, null, null);
        }
    });

    $('.employees-next-pagination').on('click', function(e) {

        e.preventDefault();

        var parent = $(this).parent();

        if (!parent.hasClass('disabled'))
        {
            var current_page_input = $('#employees-current-page');

            var current_page = parseInt(current_page_input.val()) + 1;
            var employee_type = $('#employees-employee-type').val();
            var search_string = $.trim($('#employees-list-search').val());

            current_page_input.val(current_page);

            refreshDashboard('dashboard', 'F', null, 'T', employee_type, 5, 'employees', current_page, search_string, 0, 'F', null, null, null);
        }
    });

    $('#employees-search-button').on('click', function() {

        var employee_type = $('#employees-employee-type').val();
        var search_string = $.trim($('#employees-list-search').val());

        $('.employees-current-page').val(1);

        refreshDashboard('dashboard', 'F', null, 'T', employee_type, 5, 'employees', 1, search_string, 0, 'F', null, null, null);
    });

    /*********************************************************************************/

    $('.resources-item').on('click', function(e) {

        e.preventDefault();

        var this_tem = $(this);

        if (!this_tem.hasClass('active'))
        {
            $('.resources-item').each(function() {

                $(this).removeClass('active');
            });

            this_tem.addClass('active');

            var resource_type = this_tem.attr('data-resource-type');

            $('#active-resource-type').val(resource_type);
            $('#active-list-search').val('');

            refreshDashboard('dashboard', 'F', null, 'T', null, resource_type, 'active', 1, '', 0, 'F', null, null, null);
        }
    });

    $('.active-previous-pagination').on('click', function(e) {

        e.preventDefault();

        var parent = $(this).parent();

        if (!parent.hasClass('disabled'))
        {
            var current_page_input = $('#active-current-page');

            var current_page = parseInt(current_page_input.val()) - 1;
            var resource_type = $('#active-resource-type').val();
            var search_string = $.trim($('#active-list-search').val());

            current_page_input.val(current_page);

            refreshDashboard('dashboard', 'F', null, 'T', null, resource_type, 'active', current_page, search_string, 0, 'F', null, null, null);
        }
    });

    $('.active-next-pagination').on('click', function(e) {

        e.preventDefault();

        var parent = $(this).parent();

        if (!parent.hasClass('disabled'))
        {
            var current_page_input = $('#active-current-page');

            var current_page = parseInt(current_page_input.val()) + 1;
            var resource_type = $('#active-resource-type').val();
            var search_string = $.trim($('#active-list-search').val());

            current_page_input.val(current_page);

            refreshDashboard('dashboard', 'F', null, 'T', null, resource_type, 'active', current_page, search_string, 0, 'F', null, null, null);
        }
    });

    $('#active-search-button').on('click', function() {

        var resource_type = $('#active-resource-type').val();
        var search_string = $.trim($('#active-list-search').val());

        $('.active-current-page').val(1);

        refreshDashboard('dashboard', 'F', null, 'T', null, resource_type, 'active', 1, search_string, 0, 'F', null, null, null);
    });

    /*********************************************************************************/

    $('#sites-list').on('click', '.show-site-details', function() {

        var this_resource = $(this);
        var resource_type = this_resource.attr('data-resource-type');
        var site_id = this_resource.attr('data-site-id');
        var site_name = this_resource.attr('data-site-name');
        var resource_name = this_resource.attr('data-resource-name');

        $('#site-resource-type').val(resource_type);
        $('#site-site-type').val('site');
        $('#site-site-id').val(site_id);
        $('#site-list-search-string').val('');
        $('#resources-modal-title').html(site_name);
        $('#resources-modal-subtitle').html(resource_name);

        refreshDashboard('modal', 'F', null, 'T', null, resource_type, 'site', 1, '', 0, 'T', site_id, null, null);
    });

    $('#sites-list').on('click', '.show-parking-details', function() {

        var this_resource = $(this);
        var resource_type = this_resource.attr('data-resource-type');
        var parking_id = this_resource.attr('data-parking-id');
        var parking_name = this_resource.attr('data-parking-name');
        var resource_name = this_resource.attr('data-resource-name');

        $('#site-resource-type').val(resource_type);
        $('#site-site-type').val('parking');
        $('#site-site-id').val(parking_id);
        $('#site-list-search-string').val('');
        $('#resources-modal-title').html(parking_name);
        $('#resources-modal-subtitle').html(resource_name);

        refreshDashboard('modal', 'F', null, 'T', null, resource_type, 'site', 1, '', 0, 'T', null, parking_id, null);
    });

    $('.site-previous-pagination').on('click', function(e) {

        e.preventDefault();

        var parent = $(this).parent();

        if (!parent.hasClass('disabled'))
        {
            var current_page_input = $('#site-current-page');
            var current_page = parseInt(current_page_input.val()) - 1;
            var resource_type = $('#site-resource-type').val();
            var search_string = $.trim($('#site-list-search-string').val());
            var search_filter = $('#site-list-search-filter').val();
            var site_type = $.trim($('#site-site-type').val());

            var site_id = null;
            var parking_id = null;

            if (site_type === 'site')
            {
                site_id = $.trim($('#site-site-id').val());
            }
            else
            {
                parking_id = $.trim($('#site-site-id').val());
            }

            current_page_input.val(current_page);

            refreshDashboard('modal', 'F', null, 'T', null, resource_type, 'site', current_page, search_string, search_filter, 'F', site_id,
                parking_id, null);
        }
    });

    $('.site-next-pagination').on('click', function(e) {

        e.preventDefault();

        var parent = $(this).parent();

        if (!parent.hasClass('disabled'))
        {
            var current_page_input = $('#site-current-page');
            var current_page = parseInt(current_page_input.val()) + 1;
            var resource_type = $('#site-resource-type').val();
            var search_string = $.trim($('#site-list-search-string').val());
            var search_filter = $('#site-list-search-filter').val();
            var site_type = $.trim($('#site-site-type').val());

            var site_id = null;
            var parking_id = null;

            if (site_type === 'site')
            {
                site_id = $.trim($('#site-site-id').val());
            }
            else
            {
                parking_id = $.trim($('#site-site-id').val());
            }

            current_page_input.val(current_page);

            refreshDashboard('modal', 'F', null, 'T', null, resource_type, 'site', current_page, search_string, search_filter, 'F', site_id,
                parking_id, null);
        }
    });

    $('#site-search-button').on('click', function() {

        var resource_type = $('#site-resource-type').val();
        var search_string = $.trim($('#site-list-search-string').val());
        var search_filter = $('#site-list-search-filter').val();
        var site_type = $.trim($('#site-site-type').val());

        var site_id = null;
        var parking_id = null;

        if (site_type === 'site')
        {
            site_id = $.trim($('#site-site-id').val());
        }
        else
        {
            parking_id = $.trim($('#site-site-id').val());
        }

        refreshDashboard('modal', 'F', null, 'T', null, resource_type, 'site', 1, search_string, search_filter, 'F', site_id, parking_id, null);
    });

    /*********************************************************************************/

    $('.ibox-content, #site-list-data').on('click', '.dropdown-status-item', function() {

        var this_status = $(this);
        var manipulation_type = this_status.attr('data-manipulation-type');
        var status_id = this_status.attr('data-status-id');
        var resource_type = this_status.attr('data-resource-type');
        var resource_id = this_status.attr('data-resource-id');

        var refresh_type = getSpinnerClass();

        showSpinner(refresh_type);

        doManipulation(refresh_type, manipulation_type, status_id, resource_type, resource_id);
    });

    $('.ibox-content, #site-list-data').on('click', '.dropdown-site-item', function() {

        var this_site = $(this);
        var manipulation_type = this_site.attr('data-manipulation-type');
        var site_id = this_site.attr('data-site-id');
        var resource_type = this_site.attr('data-resource-type');
        var resource_id = this_site.attr('data-resource-id');

        var refresh_type = getSpinnerClass();

        showSpinner(refresh_type);

        if (this_site.hasClass('remove'))
        {
            removeAdditionalSite(site_id, resource_id, this_site);
        }
        else
        {
            doManipulation(refresh_type, manipulation_type, site_id, resource_type, resource_id, this_site);
        }
    });

    $('.ibox-content, #site-list-data').on('click', '.dropdown-parking-item', function() {

        var this_parking = $(this);
        var manipulation_type = this_parking.attr('data-manipulation-type');
        var parking_id = this_parking.attr('data-parking-id');
        var resource_type = this_parking.attr('data-resource-type');
        var resource_id = this_parking.attr('data-resource-id');

        var refresh_type = getSpinnerClass();

        showSpinner(refresh_type);

        doManipulation(refresh_type, manipulation_type, parking_id, resource_type, resource_id);
    });
});