@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.vehicles') }}
                <a href="{{ route('AddVehicle') }}" class="btn btn-w-m btn-primary pull-right">{{ trans('main.add_vehicle') }}</a>
            </h2>
        </div>
    </div>

    @if (!$vehicles->isEmpty())
        <div class="row border-bottom white-bg dashboard-header">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="30">
                        <thead>
                        <tr>
                            <th class="footable-visible">{{ trans('main.manufacturer') }}</th>
                            <th class="footable-visible">{{ trans('main.name') }}</th>
                            <th class="footable-visible">{{ trans('main.model') }}</th>
                            <th class="footable-visible">{{ trans('main.register_number') }}</th>
                            <th class="footable-visible">{{ trans('main.status') }}</th>
                            <th class="footable-visible text-center">{{ trans('main.edit') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($vehicles as $vehicle)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $vehicle->manufacturer->name }}</td>
                                <td class="footable-visible">{{ $vehicle->name }}</td>
                                <td class="footable-visible">{{ $vehicle->model }}</td>
                                <td class="footable-visible">{{ $vehicle->register_number }}</td>
                                <td class="footable-visible">{{ $vehicle->status->name }}</td>
                                <td class="footable-visible text-center">
                                    <a href="{{ route('EditVehicle', $vehicle->id) }}"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" class="footable-visible text-center">
                                {{ $vehicles->links() }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection