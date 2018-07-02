<div class="modal inmodal" id="addInvestorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated flipInY">
            <div class="modal-header">
                <i class="fa fa-building-o modal-icon"></i>
                <h4 class="modal-title">{{ trans('main.new_investor') }}</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off')) }}
                <div class="form-group">
                    <label>{{ trans('main.name') }} *</label>
                    {{ Form::text('name', null, array('class' => 'form-control investor-name')) }}
                </div>
                <div class="form-group">
                    <label>{{ trans('main.country') }} *</label>
                    {{ Form::select('country', $countries, null, array('class' => 'form-control investor-country')) }}
                </div>
                <div class="form-group" id="investor-city-id-div">
                    <label>{{ trans('main.city') }} *</label>
                    {{ Form::select('city_id', $cities, null, array('class' => 'form-control investor-city-id')) }}
                </div>
                <div class="form-group" id="investor-city-div" style="display: none">
                    <label>{{ trans('main.city') }} *</label>
                    {{ Form::text('city', null, array('class' => 'form-control investor-city')) }}
                </div>
                <div class="form-group">
                    <label>{{ trans('main.address') }} *</label>
                    {{ Form::text('address', null, array('class' => 'form-control investor-address')) }}
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">{{ trans('main.cancel') }}</button>
                <button type="button" class="btn btn-primary insert-investor">{{ trans('main.save') }}</button>
            </div>
        </div>
    </div>
</div>