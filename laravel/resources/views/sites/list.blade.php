@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.sites') }}
                <a href="{{ route('AddSite') }}" class="btn btn-w-m btn-primary pull-right">{{ trans('main.add_site') }}</a>
            </h2>
        </div>
    </div>

    @if (!$sites->isEmpty())
        <div class="row border-bottom white-bg dashboard-header">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <table class="footable table table-stripped toggle-arrow-tiny default breakpoint footable-loaded" data-page-size="30">
                        <thead>
                        <tr>
                            <th class="footable-visible">{{ trans('main.name') }}</th>
                            <th class="footable-visible">{{ trans('main.investor') }}</th>
                            <th class="footable-visible">{{ trans('main.country') }}</th>
                            <th class="footable-visible">{{ trans('main.city') }}</th>
                            <th class="footable-visible">{{ trans('main.status') }}</th>
                            <th class="footable-visible text-center">{{ trans('main.edit') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($sites as $site)
                            <tr style="display: table-row;">
                                <td class="footable-visible">{{ $site->name }}</td>
                                <td class="footable-visible">{{ $site->investor->name }}</td>
                                <td class="footable-visible">{{ $site->country->name }}</td>
                                <td class="footable-visible">{{ $site->city }}</td>
                                <td class="footable-visible">{{ $site->status->name }}</td>
                                <td class="footable-visible text-center">
                                    <a href="{{ route('EditSite', $site->id) }}"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" class="footable-visible text-center">
                                {{ $sites->links() }}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection