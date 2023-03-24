<?php if($tab['type'] == 'form') { ?>
    <!-- form -->
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

        $defaultButtons = true;
        if(array_key_exists('defaultButtons', $form)) {
            $defaultButtons = $form['defaultButtons'];
        }
        //echo 'Default buttons -> ['.$defaultButtons.']'; die();
    ?>
    <form action="{{ url($frmAction) }}" name="{{ $frmName }}" id="{{ $frmName }}" method="post" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                {{ csrf_field() }}
                <?php foreach($tab['fields'] as $field) { ?>
                    <?php 
                        // editor
                        if ($field['type'] == 'editor') {
                            $editors[] = $field;
                        }
                        // color
                        if($field['type'] == 'color') {
                            $colors[] = $field;
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
                                        'config' => $config,
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
                                    'config' => $config,
                                ])
                            <?php } ?>
                        </fieldset>
                    <?php } else { ?>
                        @include('softinline::fields', [
                            'field' => $field,
                            'controller' => $controller,
                            'id' => $id,
                            'config' => $config,
                        ])
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="card-footer">
                <?php if(array_key_exists('optionsPostSave', $form)) { ?>
                    <button type="button" name="btn-submit-options-post-save" id="btn-submit-options-post-save" class="btn btn-primary {{ @$config['btnStyles'] }}" onclick="jcrud.submitSelectOptionPostSave()"><i class="loading"></i> {{ ucfirst(trans('messages.accept')) }}</button>
                    <input type="hidden" name="optionsPostSave" id="optionsPostSave" value="" />
                <?php } else { ?>
                    <?php if($defaultButtons) { ?>
                        <button type="button" name="btn-submit" id="btn-submit-{{ $tab['key'] }}" class="btn btn-primary {{ @$config['btnStyles'] }}" onclick="jcrud.submit('{{ $frmName }}')"><i class="loading"></i> {{ ucfirst(trans('messages.accept')) }}</button>
                    <?php } ?>
                <?php } ?>
                <?php foreach($tab['extraButtons'] as $extraButton) { ?>
                    <button type="button" class="btn btn-primary {{ @$config['btnStyles'] }}" onclick="{{ $extraButton[1] }}('{{ @$item->id }}')"> {{ ucfirst(trans('messages.'.$extraButton[0])) }}</button>
                <?php } ?>
                <?php if($defaultButtons) { ?>
                    <a href="{{ url($ajax.$config['url'].$query) }}" class="btn btn-secondary {{ @$config['btnStyles'] }}"> {{ ucfirst(trans('messages.cancel')) }}</a> 
                <?php } ?>
            </div>
        </div>
        <input type="hidden" name="tab" value="{{ $tab['key'] }}" />                                        
        <?php
            $params = \Request::all();
            $counter = count($params);
        ?>
        <?php if($counter > 0) { ?>
            <?php foreach($params as $kParam => $vParam) { ?>
                <input type="hidden" name="{{ $kParam }}" id="{{ $kParam }}" value="{{ $vParam }}" />
            <?php } ?>
        <?php } ?>
    </form>                            
<?php } elseif($tab['type'] == 'view') { ?>
    <!-- view -->
    @include($tab['view'], [
        'config' => $config,
        'item' => $item,
        'colKey' => $tab['key'],
        'config' => $config,
    ])                                
<?php } ?>