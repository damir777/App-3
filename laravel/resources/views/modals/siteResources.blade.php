<div class="modal inmodal" id="resourcesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated flipInY">
            <div class="modal-header">
                <h2 class="modal-title" id="resources-modal-title"></h2>
                <h4 class="font-bold m-t-md"><span id="resources-modal-subtitle"></span><span id="resource-counter"></span></h4>
            </div>
            <div class="modal-body site-resources-modal">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="modal-loader-div" class="sk-loading">
                            <div class="sk-spinner sk-spinner-double-bounce">
                                <div class="sk-double-bounce1"></div>
                                <div class="sk-double-bounce2"></div>
                            </div>
                            {{ Form::hidden('site_resource_type', null, array('id' => 'site-resource-type')) }}
                            {{ Form::hidden('site_current_page', null, array('id' => 'site-current-page')) }}
                            {{ Form::hidden('site_site_type', null, array('id' => 'site-site-type')) }}
                            {{ Form::hidden('site_site_id', null, array('id' => 'site-site-id')) }}
                            <form role="form" class="form-inline search-form" id="site-search-form">
                                <div class="form-group">
                                    <input type="text" placeholder="{{ trans('main.search_placeholder') }}" id="site-list-search-string" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <select id="site-list-search-filter" class="form-control" autocomplete="off"></select>
                                </div>
                                <button class="btn btn-info search-btn" id="site-search-button" type="button">{{ trans('main.search') }}</button>
                            </form>
                            <div id="site-list-data" style="display: none">
                                <div class="dataTables_paginate paging_simple_numbers" id="site-list-pagination">
                                    <ul class="pagination">
                                        <li class="paginate_button previous">
                                            <a href="#" class="site-previous-pagination">{{ trans('main.previous') }}</a>
                                        </li>
                                        <li class="paginate_button next">
                                            <a href="#" class="site-next-pagination">{{ trans('main.next') }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <table class="footable table table-striped toggle-arrow-tiny default breakpoint footable-loaded dashboard-table" data-page-size="30">
                                    <thead>
                                    <tr id="site-table-header"></tr>
                                    </thead>
                                    <tbody id="site-table-body"></tbody>
                                </table>
                            </div>
                            <div class="animated fadeInUp no-data" id="site-no-data"><h3>{{ trans('main.no_data') }}</h3></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">{{ trans('main.close') }}</button>
            </div>
        </div>
    </div>
</div>