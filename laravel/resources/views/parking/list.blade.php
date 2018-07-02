@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.parking') }}
                <a href="{{ route('AddParking') }}" class="btn btn-w-m btn-primary pull-right">{{ trans('main.add_parking') }}</a>
            </h2>
        </div>
    </div>

    @if (!$parking->isEmpty())
        <div class="row border-bottom white-bg dashboard-header">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="30">
                        <thead>
                        <tr>
                            <th class="footable-visible">{{ trans('main.name') }}</th>
                            <th class="footable-visible">{{ trans('main.address') }}</th>
                            <th class="footable-visible">{{ trans('main.status') }}</th>
                            <th class="footable-visible text-center">{{ trans('main.edit') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($parking as $parking_data)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $parking_data->name }}</td>
                                <td class="footable-visible">{{ $parking_data->address }}</td>
                                <td class="footable-visible">{{ $parking_data->status->name }}</td>
                                <td class="footable-visible text-center">
                                    <a href="{{ route('EditParking', $parking_data->id) }}"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="footable-visible text-center">
                                {{ $parking->links() }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection