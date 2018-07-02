@if ($show_dashboard == 'T')
    <li @if (Request::is('dashboard')) {{ "class=active" }} @endif>
        <a href="{{ route('DashboardPage') }}">
            <i class="fa fa-desktop"></i> <span class="nav-label">{{ trans('main.dashboard') }}</span>
        </a>
    </li>
@endif

@if ($show_overview == 'T')
    <li @if (Request::is('overview/*')) {{ "class=active" }} @endif>
        <a href="#">
            <i class="fa fa-tasks"></i> <span class="nav-label">{{ trans('main.overview') }}</span> <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level">
            <li @if (Request::is('overview/resources/1')) {{ "class=active" }} @endif>
                <a href="{{ route('ResourcesOverview', 1) }}">{{ trans('main.machines') }}</a>
            </li>
            <li @if (Request::is('overview/resources/2')) {{ "class=active" }} @endif>
                <a href="{{ route('ResourcesOverview', 2) }}">{{ trans('main.tools') }}</a>
            </li>
            <li @if (Request::is('overview/resources/3')) {{ "class=active" }} @endif>
                <a href="{{ route('ResourcesOverview', 3) }}">{{ trans('main.equipment') }}</a>
            </li>
            <li @if (Request::is('overview/resources/4')) {{ "class=active" }} @endif>
                <a href="{{ route('ResourcesOverview', 4) }}">{{ trans('main.vehicles') }}</a>
            </li>
            <li @if (Request::is('overview/resources/5')) {{ "class=active" }} @endif>
                <a href="{{ route('ResourcesOverview', 5) }}">{{ trans('main.employees') }}</a>
            </li>
        </ul>
    </li>
@endif

@if ($show_sites_and_parking == 'T')
    <li @if (Request::is('sites/*') || Request::is('parking/*')) {{ "class=active" }} @endif>
        <a href="#">
            <i class="fa fa-building"></i> <span class="nav-label">{{ trans('main.sites') }}</span> <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level">
            <li @if (Request::is('sites/*')) {{ "class=active" }} @endif>
                <a href="{{ route('GetSites') }}">{{ trans('main.sites') }}</a>
            </li>
            <li @if (Request::is('parking/*')) {{ "class=active" }} @endif>
                <a href="{{ route('GetParking') }}">{{ trans('main.parking') }}</a>
            </li>
        </ul>
    </li>
@endif

@if ($show_entry_dwa == 'T')
    <li @if (Request::is('DWA/*')) {{ "class=active" }} @endif>
        <a href="{{ route('GetDWA') }}">
            <i class="fa fa-file-text-o"></i> <span class="nav-label">{{ trans('main.dwa') }}</span>
        </a>
    </li>
@endif

<li @if (Request::is('problemReports/*')) {{ "class=active" }} @endif>
    <a href="{{ route('GetProblemReports') }}">
        <i class="fa fa-exclamation-triangle"></i> <span class="nav-label">{{ trans('main.problem_reports') }}</span>
        <span class="label label-warning pull-right problem-report-label"></span>
    </a>
</li>

@if ($show_resources == 'T')
    <li @if (Request::is('resources/*')) {{ "class=active" }} @endif>
        <a href="#">
            <i class="fa fa-list-alt"></i> <span class="nav-label">{{ trans('main.resources') }}</span> <span class="fa arrow"></span>
        </a>
        <ul class="nav nav-second-level">
            <li @if (Request::is('resources/machines/*')) {{ "class=active" }} @endif>
                <a href="{{ route('GetMachines') }}">{{ trans('main.machines') }}</a>
            </li>
            <li @if (Request::is('resources/tools/*')) {{ "class=active" }} @endif>
                <a href="{{ route('GetTools') }}">{{ trans('main.tools') }}</a>
            </li>
            <li @if (Request::is('resources/equipment/*')) {{ "class=active" }} @endif>
                <a href="{{ route('GetEquipment') }}">{{ trans('main.equipment') }}</a>
            </li>
            <li @if (Request::is('resources/vehicles/*')) {{ "class=active" }} @endif>
                <a href="{{ route('GetVehicles') }}">{{ trans('main.vehicles') }}</a>
            </li>
            <li @if (Request::is('resources/employees/*')) {{ "class=active" }} @endif>
                <a href="{{ route('GetEmployees') }}">{{ trans('main.employees') }}</a>
            </li>
        </ul>
    </li>
@endif

@if ($show_statistic == 'T')
    <li @if (Request::is('statistic')) {{ "class=active" }} @endif>
        <a href="{{ route('StatisticPage') }}">
            <i class="fa fa-bar-chart"></i> <span class="nav-label">{{ trans('main.statistic') }}</span>
        </a>
    </li>
@endif