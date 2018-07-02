@extends('layouts.general')

@section('content')
    <div class="row border-bottom white-bg dashboard-header">
        <div class="col-sm-12">
            <h2>{{ trans('main.statistic') }}</h2>
        </div>
    </div>
    <div class="row border-bottom white-bg dashboard-header">
        <div class="row">
            <div class="col-lg-4">
                <div class="widget style1 navy-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-6">
                            <h3>Trenutni broj zaposlenih</h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <h2 class="font-bold"><span id="active-employees">{{ $statistic['active_employees'] }}</span></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="widget style1 navy-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-6">
                            <h3>Broj zaposlenih na određeno vrijeme</h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <h2 class="font-bold"><span id="fixed-term-contract">{{ $statistic['fixed_term_contract'] }}</span></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="widget style1 navy-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-6">
                            <h3>Broj zaposlenih na neodređeno vrijeme</h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <h2 class="font-bold"><span id="indefinite-contract">{{ $statistic['indefinite_contract'] }}</span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-2">
                <div class="widget style1 navy-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-6">
                            <h3>Broj zaposlenih muškaraca</h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <h2 class="font-bold"><span id="men">{{ $statistic['men'] }}</span></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="widget style1 navy-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-6">
                            <h3>Broj zaposlenih žena</h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <h2 class="font-bold"><span id="women">{{ $statistic['women'] }}</span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-t-lg">
            <div class="col-sm-12">
                {{ Form::open(array('url' => '#')) }}
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>{{ trans('main.work_type') }}</label>
                                {{ Form::select('work_type', $work_types, null, array('class' => 'form-control work-type')) }}
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <script>

        var error = '{{ trans('errors.error') }}';

    </script>

    {{ HTML::script('js/functions/statistic.js?v='.date('YmdHi')) }}
@endsection