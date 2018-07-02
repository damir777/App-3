<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <style>
        * {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 9px;
        }
    </style>

    {{ HTML::style('css/bootstrap.min.css') }}
</head>
<body>

<h3>{{ $page_title }}</h3>
<br>

<table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="5000">
    <thead>
    <tr>
        @foreach ($table_header as $header)
            <th class="footable-visible">{{ $header }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>

    @foreach ($resources as $resource)
        <tr style="display: table-row;">
            <td class="footable-visible">{{ $resource['code'] }}</td>
            @if ($type != 5)
                <td class="footable-visible">{{ $resource['manufacturer'] }}</td>
                <td class="footable-visible">{{ $resource['name'] }}</td>
                <td class="footable-visible">{{ $resource['model'] }}</td>
            @else
                <td class="footable-visible">{{ $resource['name'] }}</td>
                <td class="footable-visible">{{ $resource['work_type'] }}</td>
                <td class="footable-visible">{{ $resource['oib'] }}</td>
            @endif
            <td>{{ $resource['current_site'] }}</td>
            @if ($type != 5)
                <td class="footable-visible">{{ $resource['current_parking'] }}</td>
            @else
                <td class="footable-visible">{{ $resource['current_status'] }}</td>
            @endif
        </tr>
    @endforeach

    </tbody>
</table>

</body>
</html>