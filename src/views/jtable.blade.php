<?php   
    // wrapper extends
    $wrapper = !$list['wrapper'] ? 'softinline::jtable_wrapper' : $list['wrapper'];
    
    $query = http_build_query(\Request::all());
    if($query != '') {
        $query = '?'.$query;
    }
        
    // check if use ajax
    $ajax = @$config['ajax'] ? '#' : '';
?>
@extends($wrapper, [
    'breadcrumb' => $breadcrumb
])
@section('body')
    <div class="row">
        <div class="col-lg-12 text-right">
            <?php if($list['actions']['selector']) { ?>
                <a href="javascript:void(0)" class="btn btn-primary {{ @$config['btnStyles'] }} select-all-btn" data-datatable="{{ $list['name'] }}" data-url="{{ $config['url'] }}"><i class="fa fa-check"></i> {{ ucfirst(trans('messages.select-all')) }}</a>
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
                                    <a href="javascript:void(0)" class="dropdown-item" onclick="{{ $tmp[1] }}(this)" data-url="{{ $config['url'] }}" data-datatable="{{ $list['name'] }}" data-id="{{ @$id }}"><i class="{{ $listOption[1] }}"></i> {{ ucfirst(trans('messages.'.$listOption[0])) }}</a>
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
                    <a href="javascript:void(0)" class="btn btn-primary {{ @$config['btnStyles'] }}" name="{{ $extraAction[3] }}" id="{{ $extraAction[3] }}" onclick="{{ $extraAction[2] }}(this)" data-url="{{ $config['url'] }}" data-datatable="{{ $list['name'] }}"><i class="{{ $extraAction[1] }}"></i> {{ ucfirst(trans('messages.'.$extraAction[0])) }}</a>
                <?php } ?>
            <?php } ?>
            <?php if($list['actions']['export']) { ?>
                <a href="javascript:void(0)" class="btn btn-primary {{ @$config['btnStyles'] }}" onclick="crud.export(this)" data-url="{{ $config['url'] }}" data-datatable="{{ $list['name'] }}">
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
    <div class="row">
        <div class="col-lg-12">
            <div class="table">
                <?php
                    // default table class
                    $class = !$list['class'] ? 'table table-hover table-bordered' : $list['class'];
                ?>
                <table id="table-crud-{{ $list['name'] }}" class="{{ $class }}" style="width:100%">
                    <thead>
                        <tr>
                            <?php if($list['actions']['selector']) { ?>
                                <th><input type="checkbox" id="chk-select-all" name="chk-select-all" class="select-all-btn" data-datatable="{{ $list['name'] }}" data-url="{{ $config['url'] }}" /></th>
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
                                ?>
                                <th <?php echo $options; ?>>{{ $title }}</th>
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
                                <?php if(array_key_exists('searchable', $col)) { ?>
                                    <?php if($col['searchable']) { ?>
                                        <th data-searchable="true"></th>
                                    <?php } else { ?>
                                        <th></th>
                                    <?php } ?>
                                <?php } else { ?>
                                    <th data-searchable="true"></th>
                                <?php } ?>
                            <?php } ?>
                        </tfoot>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
@stop
@section('script')
    @parent
    <script>
        $(function() {
        
            if(crud.tables["{{ $list['name'] }}"] == undefined || crud.tables["{{ $list['name'] }}"] == 'undefined') {
                crud.tables["{{ $list['name'] }}"] = Array();
                crud.tables["{{ $list['name'] }}"].datatable = null;
                crud.tables["{{ $list['name'] }}"].selected = Array();
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
            
            crud.tables["{{ $list['name'] }}"].datatable = $('#table-crud-{{ $list['name'] }}').DataTable({
                "sDom":"<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>\n        <'table-responsive'tr>\n        <'row align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>",
                /*"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",*/
                "stateSave": true,
                "processing": true,
                "serverSide": true,
                /*"responsive": true,*/
                "ajax": "<?php echo $data; ?>",
                "order": [[ {{ $orderCol }}, "{{ $orderType }}" ]],                
                "pageLength": {{ @$list['pageLength'] != '' ? $list['pageLength'] : 10}},
                "columns": [
                    <?php if($list['actions']['selector']) { ?>
                            { data:"selector", name:"selector", orderable:false, searchable:false },
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
                        ?>
                        { data: "{{ $col['field'] }}", name: "{{ $col['name'] }}", orderable: {{ $orderable }}, searchable: {{ $searchable }} },
                    <?php } ?>
                ],
                "rowCallback": function( row, data ) {
                    <?php if($list['actions']['selector']) { ?>
                        var id = data.DT_RowId.split('_');    
                        if ( $.inArray(id[1], crud.tables["{{ $list['name'] }}"].selected) !== -1 ) {
                            $(row).find('.selector').prop('checked', true);
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