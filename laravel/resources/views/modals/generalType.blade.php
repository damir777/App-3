<div class="modal inmodal" id="addGeneralTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated flipInY">
            <div class="modal-header">
                <i class="fa fa-cogs modal-icon"></i>
                <h4 class="modal-title">{{ trans('main.new_type') }}</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '#', 'autocomplete' => 'off')) }}
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-group">
                            <label>{{ trans('main.name') }} *</label>
                            {{ Form::text('name', null, array('class' => 'form-control type-name')) }}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">{{ trans('main.cancel') }}</button>
                <button type="button" class="btn btn-primary insert-general-type">{{ trans('main.save') }}</button>
            </div>
        </div>
    </div>
</div>