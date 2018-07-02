<div class="modal inmodal" id="locationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated flipInY">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="gMap" style="width: 100%; height: 450px"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-6">
                        <div  class="text-left" style="margin-top: 7px">Kliknite na kartu za odabir lokacije</div>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success" data-dismiss="modal">{{ trans('main.ok') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{ HTML::script('http://maps.googleapis.com/maps/api/js?key=AIzaSyDPatFLhUHzUaKaGiviHNXYGuudwpKg-EY') }}