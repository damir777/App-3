<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>xx</title>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}
    {{ HTML::style('css/animate.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/plugins/toastr/toastr.min.css') }}

    {{ HTML::script('js/jquery-3.1.1.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/main/auth.js') }}
    {{ HTML::script('js/plugins/toastr/toastr.min.js') }}
</head>
<body class="gray-bg">
@yield('content')

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

</body>
</html>