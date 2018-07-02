<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xx</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link rel="icon" href="{{ URL::to('/').'/favicon.ico' }}">

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}
    {{ HTML::style('css/animate.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/plugins/datapicker/datepicker3.css') }}
    {{ HTML::style('css/plugins/toastr/toastr.min.css') }}
    {{ HTML::style('css/plugins/iCheck/custom.css') }}
    {{ HTML::style('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}
    {{ HTML::style('css/plugins/clockpicker/clockpicker.css') }}
    {{ HTML::style('css/plugins/blueimp/css/blueimp-gallery.min.css') }}
    {{ HTML::style('css/custom.css') }}

    <!-- Mainly scripts -->
    {{ HTML::script('js/jquery-3.1.1.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/plugins/metisMenu/jquery.metisMenu.js') }}
    {{ HTML::script('js/plugins/slimscroll/jquery.slimscroll.min.js') }}

    <!-- Custom and plugin javascript -->
    {{ HTML::script('js/inspinia.js') }}
    {{ HTML::script('js/plugins/pace/pace.min.js') }}
    {{ HTML::script('js/plugins/datapicker/bootstrap-datepicker.js') }}
    {{ HTML::script('js/plugins/toastr/toastr.min.js') }}
    {{ HTML::script('js/plugins/iCheck/icheck.min.js') }}
    {{ HTML::script('js/plugins/clockpicker/clockpicker.js') }}
    {{ HTML::script('js/plugins/blueimp/jquery.blueimp-gallery.min.js') }}
    {{ HTML::script('js/main/general.js') }}
    {{ HTML::script('js/main/date.js') }}

    <script type="text/javascript">
        var ajax_url = '<?php echo URL::to('/'); ?>/';
    </script>
</head>
<body>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <h2 class="profile-element text-center" style="margin: 0; padding: 0;">xx</h2>
                    <div class="logo-element">IN+</div>
                </li>
                @include('menu')
            </ul>
        </div>
    </nav>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message"><strong>Dobro došli, {{ $username }} ({{ $user_role }})</strong></span>
                    </li>
                    <li>
                        <a href="{{ route('LogoutUser') }}">
                            <i class="fa fa-sign-out"></i> {{ trans('main.logout') }}
                        </a>
                    </li>
                    <li>
                        <a class="right-sidebar-toggle">
                            <i class="fa fa-tasks" style="visibility: hidden"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="wrapper wrapper-content">
            @yield('content')
        </div>
    </div>

    <script>

        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/problemReports.js?v='.date('YmdHi')) }}

</div>

@if (Session::has('error_message'))
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.error("{{ Session::get('error_message') }}");
        });
    </script>
@endif

@if (Session::has('success_message'))
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.success("{{ Session::get('success_message') }}");
        });
    </script>
@endif

@if (Session::has('info_message'))
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.info("{{ Session::get('info_message') }}");
        });
    </script>
@endif

@if (Session::has('warning_message'))
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.warning("{{ Session::get('warning_message') }}");
        });
    </script>
@endif

</body>
</html>