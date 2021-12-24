<?php   
    // wrapper extends
    $wrapper = !$form['wrapper'] ? 'softinline::jform_wrapper' : $form['wrapper'];

    $query = http_build_query(\Request::all());
    if($query != '') {
        $query = '?'.$query;
    }

    // check if use ajax
    $ajax = @$config['ajax'] ? '#' : '';
?>
@extends($wrapper)
@section('body')
    <?php 
        $editors = [];
    ?>    
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <?php $first = true; ?>
                <?php foreach($form['tabs'] as $tab) { ?>
                    <?php
                        $show = true;
                        if(array_key_exists('condition', $tab)) {
                            $method = $tab['condition'];
                            $show = $controller::$method(@$item);
                        }
                    ?>
                    <?php if($show) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $first ? 'active' : ''; ?>" href="#tab_{{ $tab['key'] }}" data-toggle="tab" aria-expanded="false">{{ ucfirst(trans('messages.'.$tab['title'])) }}</a>
                        </li>
                        <?php $first = false; ?>
                    <?php } ?>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php $first = true; ?>
                <?php foreach($form['tabs'] as $tab) { ?>                
                    <?php
                        $show = true;
                        if(array_key_exists('condition', $tab)) {
                            $method = $tab['condition'];
                            $show = $controller::$method(@$item);
                        }
                    ?>
                    <?php if($show) { ?>
                        <div class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="tab_{{ $tab['key'] }}">
                            <!-- form -->
                            <?php if($tab['type'] == 'form') { ?>
                                <?php
                                    // default values
                                    $frmAction = @$item ? '/'.$config['url'].'/'.$item->id.'/update' : '/'.$config['url'].'/create';
                                    $frmName = 'frm-'.$config['title'].'-'.$tab['key'];

                                    // if has action
                                    if(array_key_exists('action', $form)) {
                                        $frmAction = $item ? '/'.$config['url'].'/'.$item->id.'/'.$form['action'] : '/'.$config['url'].'/'.$form['action'];
                                        $frmName = 'frm-'.$config['title'].'-'.$tab['key'];
                                    }

                                    $frmName = strtolower($frmName);                                
                                ?>
                                <form action="{{ url($frmAction) }}" name="{{ $frmName }}" id="{{ $frmName }}" method="post" enctype="multipart/form-data">
                                    <div class="card">
                                        <div class="card-body">
                                            {{ csrf_field() }}
                                            <?php foreach($tab['fields'] as $field) { ?>
                                                <?php 
                                                    // editor
                                                    if ($field['type'] == 'editor') {
                                                        $editors[] = $field['field'];
                                                    }
                                                ?>
                                                <?php if($field['type'] == 'row') { ?>
                                                    <div class="row">
                                                        <?php foreach($field['fields'] as $subfield) { ?>
                                                            <div class="col-lg-{{ (12 / count($field['fields'])) }}">
                                                                @include('softinline::fields', [
                                                                    'field' => $subfield,
                                                                    'controller' => $controller,
                                                                    'id' => $id,
                                                                ])
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } elseif($field['type'] == 'fieldset') { ?>
                                                    <fieldset>
                                                        <legend>{{ $field['title'] }}</legend>
                                                        <?php foreach($field['fields'] as $subfield) { ?>
                                                            @include('softinline::fields', [
                                                                'field' => $subfield,
                                                                'controller' => $controller,
                                                                'id' => $id,
                                                            ])
                                                        <?php } ?>
                                                    </fieldset>
                                                <?php } else { ?>
                                                    @include('softinline::fields', [
                                                        'field' => $field,
                                                        'controller' => $controller,
                                                        'id' => $id,
                                                    ])
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" name="btn-submit" id="btn-submit" class="btn btn-primary btn-sm" onclick="crud.submit('{{ $frmName }}')"><i class="loading"></i> {{ ucfirst(trans('messages.accept')) }}</button>
                                            <?php foreach($tab['extraButtons'] as $extraButton) { ?>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="{{ $extraButton[1] }}('{{ @$item->id }}')"> {{ ucfirst(trans('messages.'.$extraButton[0])) }}</button>
                                            <?php } ?>
                                            <a href="{{ url($ajax.$config['url'].$query) }}" class="btn btn-secondary btn-sm"> {{ ucfirst(trans('messages.cancel')) }}</a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="tab" id="tab" value="{{ $tab['key'] }}" />                                
                                </form>
                            <!-- view -->
                            <?php } elseif($tab['type'] == 'view') { ?>                                
                                @include($tab['view'], [
                                    'config' => $config,
                                    'item' => $item,
                                    'colKey' => $tab['key'],
                                ])                                
                            <?php } ?>                          
                        </div>
                        <?php $first = false; ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <?php if(count($editors) > 0) { ?>        
        <script>
            // ckeditor
            var options = {
                skin:'kama',         
                enterMode: CKEDITOR.ENTER_BR,
                shiftEnterMode: CKEDITOR.ENTER_BR,
                /*extraPlugins:'justify',*/
            };
            <?php foreach($editors as $editor) { ?>
                CKEDITOR.replace('{{ $editor }}',options);
            <?php } ?>
        </script>
    <?php } ?>
@endsection