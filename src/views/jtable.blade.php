<?php   
    // wrapper extends
    $wrapper = !$list['wrapper'] ? 'softinline::jtable-wrapper' : $list['wrapper'];
    
    $query = http_build_query(\Request::all());
    if($query != '') {
        $query = '?'.$query;
    }
        
    // check if use ajax
    $ajax = @$config['ajax'] ? '#' : '';

    // name
    $name = $list['name'];

    // config cols
    $configCols = false;
    if(array_key_exists('configCols', $list)) {
        $configCols = $list['configCols'];        
    }

    if($configCols) {

        // get config in database for config cols
        if(\Auth::user()) {

            $datatableConfigCols = \Softinline\JCrud\Models\SfwDatatable::select()
                ->where('datatable', '=', $name)
                ->where('user_id', '=', \Auth::user()->id)
                ->first();

        }
        else {

            $datatableConfigCols = \Softinline\JCrud\Models\SfwDatatable::select()
                ->where('datatable', '=', $name)            
                ->first();

        }

        // if found one config, then override
        if($datatableConfigCols) {

            $list['cols'] = json_decode($datatableConfigCols->config, true);

        }

    }
?>
@extends($wrapper, [
    'breadcrumb' => $breadcrumb
])
@section('body')
    <?php if(@$list['headerTemplate']) { ?>
        @include($list['headerTemplate'])
    <?php } ?>
    <?php if(@$list['subLists']) { ?>
        <div class="row">
            <div class="col-lg-12">
                @include('softinline::sublists', [                    
                    'list' => $list,                    
                    'ajax' => $ajax,
                ])
            </div>
        </div>
    <?php } ?>    
    <div class="row">
        <div class="col-lg-4">
            <?php if(@$list['fastFilters']) { ?>
                <div class="row">
                    <div class="col-lg-12">
                        @include('softinline::fast-filters', [                    
                            'list' => $list,                    
                            'ajax' => $ajax,
                        ])
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-lg-8 text-right">
            <?php if($list['actions']['selector']) { ?>
                <a href="javascript:void(0)" class="btn btn-primary {{ @$config['btnStyles'] }} jcrud-select-all-btn" jcrud-data-datatable="{{ $list['name'] }}" jcrud-data-url="{{ $config['url'] }}"><i class="fa fa-check"></i> {{ ucfirst(trans('messages.select-all')) }}</a>
            <?php } ?>
            <?php if(count($list['options']) > 0) { ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary {{ @$config['btnStyles'] }}">{{ ucfirst(trans('messages.selected-actions')) }}</button>
                    <button type="button" class="btn btn-primary {{ @$config['btnStyles'] }} dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                        <div class="dropdown-menu dropdown-menu-right" role="menu" x-placement="bottom-start">
                            <?php foreach($list['options'] as $listOption) { ?>
                                <?php 
                                    // prepare action link
                                    $tmp = explode(':', $listOption[2]);
                                ?>
                                <?php if($tmp[0] == 'js') { ?>
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="{{ $tmp[1] }}(this)" jcrud-data-url="{{ $config['url'] }}" jcrud-data-datatable="{{ $list['name'] }}" jcrud-data-id="{{ @$id }}"><i class="{{ $listOption[1] }}"></i> {{ ucfirst(trans('messages.'.$listOption[0])) }}</a>
                                <?php } else { ?>
                                    <?php $tmp[1] = str_replace('{id}', @$id, $tmp[1]); ?>
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="window.open('{{ url($tmp[1]) }}','_self')"><i class="{{ $listOption[1] }}"></i> {{ ucfirst(trans('messages.'.$listOption[0])) }}</a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </button>                                
                </div>
            <?php } ?>
            <?php if(array_key_exists('extraActions', $list)) { ?>
                <?php foreach($extraActions as $extraAction) { ?>
                    <a href="javascript:void(0)" class="btn btn-primary {{ @$config['btnStyles'] }}" name="{{ $extraAction[3] }}" id="{{ $extraAction[3] }}" onclick="{{ $extraAction[2] }}(this)" jcrud-data-url="{{ $config['url'] }}" jcrud-data-datatable="{{ $list['name'] }}"><i class="{{ $extraAction[1] }}"></i> {{ ucfirst(trans('messages.'.$extraAction[0])) }}</a>
                <?php } ?>
            <?php } ?>
            <?php if($list['actions']['export']) { ?>
                <a href="javascript:void(0)" class="btn btn-primary {{ @$config['btnStyles'] }}" onclick="jcrud.export(this)" jcrud-data-url="{{ $config['url'] }}" jcrud-data-datatable="{{ $list['name'] }}">
                    <i class="fa fa-file"></i> {{ ucfirst(trans('messages.export')) }}
                </a>
            <?php } ?>
            <?php if($list['actions']['add']) { ?>
                <a href="{{ url($ajax.$config['url'].'/add'.$query) }}" class="btn btn-primary {{ @$config['btnStyles'] }}">
                    <i class="fa fa-plus-circle"></i> {{ ucfirst(trans('messages.add')) }}
                </a>
            <?php } ?>
        </div>
    </div>
    <br />    
    <?php if($configCols) { ?>
        <div class="row">
            <div class="col-lg-12 text-right text-end mb-2">
                <button class="btn btn-primary" onclick="$('#sfwcomponent-datatable-config-columns-{{ $name }}').modal('show')"><i class="las la-cog"></i></button>
            </div>
        </div>
        <div class="modal fade show" tabindex="-1" id="sfwcomponent-datatable-config-columns-{{ $name }}" aria-modal="true" role="dialog">
            <div class="modal-dialog  modal-lg ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Configurar Columnas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>                        
                    </div>
                    <div class="modal-body">
                        <form id="sfwcomponent-form-datatable-config-columns-{{ $name }}" name="sfwcomponent-form-datatable-config-columns-{{ $name }}">                            
                            <ul id="sortable">                                                            
                                <?php foreach($list['cols'] as $col) { ?>
                                    <?php
                                        // defult false is not show
                                        $default = true;
                                        if(array_key_exists('default', $col)) {
                                            $default = $col['default'];
                                        }
                                        // title                                               
                                        $title = $col['field'];
                                        if(array_key_exists('title', $col)) {
                                            $title = $col['title'];
                                        }
                                        if($title != '') {
                                            $title = ucfirst(trans('messages.'.$title));
                                        }
                                    ?>
                                    <li class="ui-state-default" id="{{ $col['field'] }}">
                                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                        <input type="checkbox" id="check-{{ $col['field'] }}" name="check-{{ $col['field'] }}" value="1" <?php echo $default ? 'checked' : ''; ?>> {{ $title }}
                                    </li>                                                                        
                                    <input type="hidden" name="json-{{ $col['field'] }}" id="json-{{ $col['field'] }}" value="<?php echo htmlspecialchars(json_encode($col), ENT_QUOTES, 'UTF-8'); ?>" />
                                <?php } ?>
                            </ul>                            
                            <input type="hidden" name="name" id="name" value="{{ $name }}" />
                            <input type="hidden" name="file" id="file" value="{{ $name }}" />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="jcrud.datatableConfigColumnsSave('sfwcomponent-form-datatable-config-columns-{{ $name }}')">Guardar</button>                        
                    </div>
                </div>
            </div>
        </div>
        <script>
            $( "#sortable" ).sortable();
        </script>
    <?php } ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="table">
                <?php
                    // default table class
                    $class = !$list['class'] ? 'table table-hover table-bordered' : $list['class'];
                ?>
                <table id="table-jcrud-{{ $list['name'] }}" class="{{ $class }}" style="width:100%">
                    <thead>
                        <tr>
                            <?php if($list['actions']['selector']) { ?>
                                <th><input type="checkbox" id="chk-select-all" name="chk-select-all" class="jcrud-select-all-btn" jcrud-data-datatable="{{ $list['name'] }}" jcrud-data-url="{{ $config['url'] }}" /></th>
                            <?php } ?>
                            <?php foreach($list['cols'] as $col) { ?>
                                <?php         
                                    // title                                               
                                    $title = $col['field'];
                                    if(array_key_exists('title', $col)) {
                                        $title = $col['title'];
                                    }
                                    if($title != '') {
                                        $title = ucfirst(trans('messages.'.$title));
                                    }
                                    // options
                                    $options = '';
                                    if(array_key_exists('options', $col)) {
                                        $options = $col['options'];
                                    }
                                    // defult false is not show
                                    $default = true;
                                    if(array_key_exists('default', $col)) {
                                        $default = $col['default'];
                                    }
                                ?>                                
                                <?php if($default) { ?>
                                    <th <?php echo $options; ?>>{{ $title }}</th>
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    </thead>                    
                    <tbody>
                    </tbody>
                    <?php if(@$list['footer']) { ?>
                        <tfoot>
                            <?php if($list['actions']['selector']) { ?>
                                <th></th>
                            <?php } ?>
                            <?php foreach($list['cols'] as $col) { ?>
                                <?php
                                    // defult false is not show
                                    $default = true;
                                    if(array_key_exists('default', $col)) {
                                        $default = $col['default'];
                                    }
                                ?>
                                <?php if($default) { ?>
                                    <?php if(array_key_exists('searchable', $col)) { ?>
                                        <?php if($col['searchable']) { ?>
                                            <th jcrud-data-searchable="true"></th>
                                        <?php } else { ?>
                                            <th></th>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <th jcrud-data-searchable="true"></th>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </tfoot>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <?php if(@$list['footerTemplate']) { ?>
        @include($list['footerTemplate'])
    <?php } ?>
@stop
@section('script')
    @parent
    <script>
        $(function() {
        
            if(jcrud.tables["{{ $list['name'] }}"] == undefined || jcrud.tables["{{ $list['name'] }}"] == 'undefined') {
                jcrud.tables["{{ $list['name'] }}"] = Array();
                jcrud.tables["{{ $list['name'] }}"].datatable = null;
                jcrud.tables["{{ $list['name'] }}"].selected = Array();
            }
        
            <?php                
                $data = url($config['url'].'/data'.$query);
                if(array_key_exists('data', $list)) {
                    $data = str_replace('{id}', $id, $list['data'].$query);
                }

                $orderCol = '0';
                $orderType = 'desc';
                if(array_key_exists('order', $list)) {
                    $orderCol = $list['order']['col'];
                    $orderType = $list['order']['type'];
                }

            ?>
            
            jcrud.tables["{{ $list['name'] }}"].datatable = $('#table-jcrud-{{ $list['name'] }}').DataTable({
                "sDom":"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>\n        <'table-responsive'tr>\n        <'row align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>",
                /*"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",*/
                "stateSave": true,
                "processing": true,
                "serverSide": true,
                /*"responsive": true,*/
                "ajax": "<?php echo $data; ?>",
                "order": [[ {{ $orderCol }}, "{{ $orderType }}" ]],                
                "pageLength": {{ @$list['pageLength'] != '' ? $list['pageLength'] : 10}},
                <?php if(isset($list['aLengthMenu'])) { ?>
                    "aLengthMenu": {!! $list['aLengthMenu'] !!}, // [[1, 10, 25, 500, -1], [1, 10, 25, 500, "All"]]
                <?php } ?>
                "columns": [
                    <?php if($list['actions']['selector']) { ?>
                        { width:"1%", data:"selector", name:"selector", orderable:false, searchable:false },
                    <?php } ?>
                    <?php foreach($list['cols'] as $col) { ?>
                        <?php
                            $orderable = 'true';
                            if(@$col['orderable']===false) {
                                $orderable = 'false';
                            }
                            $searchable = 'true';
                            if(@$col['searchable']===false) {
                                $searchable = 'false';
                            }
                            // defult false is not show
                            $default = true;
                            if(array_key_exists('default', $col)) {
                                $default = $col['default'];
                            }
                        ?>
                        <?php if($default) { ?>
                            { width:"{{ @$col['width'] }}", data:"{{ $col['field'] }}", name:"{{ $col['name'] }}", orderable:{{ $orderable }}, searchable:{{ $searchable }} },
                        <?php } ?>
                    <?php } ?>
                ],
                "rowCallback": function( row, data ) {
                    <?php if($list['actions']['selector']) { ?>
                        var id = data.DT_RowId.split('_');    
                        if ( $.inArray(id[1], jcrud.tables["{{ $list['name'] }}"].selected) !== -1 ) {
                            $(row).find('.jcrud-selector').prop('checked', true);
                        }
                    <?php } ?>
                    @if($list['rowCallBack']))
                        @include($list['rowCallBack'], [
                            'config' => $config,
                        ])
                    @endif
                },
                "drawCallback": function(settings, json) {
                    @if($list['drawCallBack'])
                        @include($list['drawCallBack'], [
                            'config' => $config,
                        ]);
                    @endif
                },
                @if(array_key_exists('extra', $list))
                    @include($list['extra'])
                @endif
            });

        });
    </script>
@stop