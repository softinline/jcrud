<?php   
    // wrapper extends
    $wrapper = !$form['wrapper'] ? 'softinline::jform-wrapper' : $form['wrapper'];

    $query = http_build_query(\Request::all());
    if($query != '') {
        $query = '?'.$query;
    }

    // check if use ajax
    $ajax = @$config['ajax'] ? '#' : '';

    // check if use languages
    $languages= false;
    if(array_key_exists('languages', $config)) {
        $method = $config['languages'];
        $languages = $controller::$method(@$item);
    }

    // get first tab
    $tab = null;
    foreach($form['tabs'] as $tabObj) {
        if($tab == null) {
            $tab = $tabObj;
        }
    }

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
@extends($wrapper, [
    'breadcrumb' => $breadcrumb
])
@section('body')
    <?php 
        $editors = [];  // array of elements for editorCK
        $colors = [];   // array of elements for color picker
    ?>    
    <?php if(@$form['headerTemplate']) { ?>
        @include($form['headerTemplate'], [
            'item' => @$item
        ])
    <?php } ?>
    <div class="row">
        <div class="col-lg-12">            
            <?php if(count($form['tabs']) > 1) { ?>                
                @include('softinline::jform-tabs', [
                    'form' => $form,
                    'item' => @$item,
                ])
            <?php } else { ?>                
                @include('softinline::jform-content', [
                    'form' => $form,
                    'tab' => $tab,
                    'item' => @$item,
                ])
            <?php } ?>            
        </div>
    </div>
    <?php if(@$form['footerTemplate']) { ?>
        @include($form['footerTemplate'], [
            'item' => @$item
        ])
    <?php } ?>
    <?php // prepare modal for options post save ?>
    <?php if(array_key_exists('optionsPostSave', $form)) { ?>
        <div class="modal" tabindex="-1" role="dialog" id="modal-options-post-save">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ ucfirst(trans('messages.select-post-option')) }} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- default option for save -->
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="button" name="btn-submit" id="btn-submit" class="btn btn-primary btn-block {{ @$config['btnStyles'] }}" onclick="jcrud.submit('{{ $frmName }}')"><i class="loading"></i> {{ ucfirst(trans('messages.accept')) }}</button>
                            </div>
                        </div>
                        <!-- other options -->
                        <?php foreach($form['optionsPostSave'] as $optionPostSaveKey => $optionPostSaveValue) { ?>
                            <div class="row" style="margin-top:10px">
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-secondary btn-block {{ @$config['btnStyles'] }}" onclick="$('#optionsPostSave').val('{{ $optionPostSaveValue[0] }}'); jcrud.submit('{{ $frmName }}')"><i class="{{ $optionPostSaveValue[2] }}"></i> {{ ucfirst(trans('messages.'.$optionPostSaveValue[1])) }}</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>                    
                </div>
            </div>
        </div>
    <?php } ?>

    <?php // add extra elements ?>
    <script>
        $(function() {
            // colors
            <?php if(count($colors) > 0) {?>
                <?php foreach($colors as $color) { ?>
                    $("#{{ $color['field'] }}").colorpicker();
                <?php } ?>
            <?php } ?>
            // editors
            <?php if(count($editors) > 0) { ?>                            
                if(!window.CKEDITOR) {
                    alert('CKEditor not found!');
                }
                var options = {
                    skin:'kama',         
                    enterMode: CKEDITOR.ENTER_BR,
                    shiftEnterMode: CKEDITOR.ENTER_BR,
                    /*extraPlugins:'justify',*/
                };
                <?php foreach($editors as $editor) { ?>
                    CKEDITOR.replace('{{ $editor['field'] }}',options);
                    <?php if(array_key_exists('translate', $editor)) { ?>
                        <?php if($editor['translate']) { ?>
                            <?php foreach($languages as $language) { ?>
                                CKEDITOR.replace('{{ $editor['field'] }}_{{ $language->iso }}',options);
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        });        
    </script>
@endsection