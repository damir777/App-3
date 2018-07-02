@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.employees') }}
                <a href="{{ route('AddEmployee') }}" class="btn btn-w-m btn-primary pull-right">{{ trans('main.add_employee') }}</a>
            </h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-lg-12">
            {{ Form::open(array('route' => 'GetEmployees', 'class' => 'form-inline', 'method' => 'get', 'autocomplete' => 'off')) }}
                <div class="form-group">
                    {{ Form::text('search_string', $search_string, array('class' => 'form-control',
                        'placeholder' => trans('main.search_placeholder'))) }}
                </div>
                <div class="form-group">
                    {{ Form::select('work_type', $work_types, $work_type, array('class' => 'form-control')) }}
                </div>
                <button class="btn btn-white">{{ trans('main.search') }}</button>
            {{ Form::close() }}
        </div>
    </div>

    @if (!$employees->isEmpty())
        <div class="row border-bottom white-bg dashboard-header">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="30">
                        <thead>
                        <tr>
                            <th class="footable-visible">{{ trans('main.employee_name') }}</th>
                            <th class="footable-visible">{{ trans('main.work_type') }}</th>
                            <th class="footable-visible">{{ trans('main.contract_type') }}</th>
                            <th class="footable-visible">{{ trans('main.oib') }}</th>
                            <th class="footable-visible">{{ trans('main.status') }}</th>
                            <th class="footable-visible text-center">{{ trans('main.edit') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($employees as $employee)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $employee->name }}</td>
                                <td class="footable-visible">{{ $employee->workType->name }}</td>
                                <td class="footable-visible">{{ $employee->contractType->name }}</td>
                                <td class="footable-visible">{{ $employee->oib }}</td>
                                <td class="footable-visible">{{ $employee->status->name }}</td>
                                <td class="footable-visible text-center">
                                    <a href="{{ route('EditEmployee', $employee->id) }}"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" class="footable-visible text-center">
                                @if ($search_string || $work_type)
                                    {{ $employees->appends(['search_string' => $search_string, 'work_type' => $work_type]) }}
                                @else
                                    {{ $employees->links() }}
                                @endif
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection