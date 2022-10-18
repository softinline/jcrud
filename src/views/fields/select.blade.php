<?php 
    $method = $field['selector'];
    $options = $controller::$method(@$item, @$id);
?>
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <select name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">        
        <option value="">{{ ucfirst(trans('messages.select-option')) }}</option>
        <?php foreach($options as $optionKey => $optionValue) { ?>
            <?php
                $selected = '';  
                // determine default value
                // if edit, dont take effect
                // on add check if Request has a param with this value    
                if($item) {
                    $selected = $optionKey == @$item->{$field['field']} ? 'selected' : '';
                }
                else {
                    $selected = $optionKey == \Request::get($field['field']) ? 'selected' : '';
                }
            ?>
            <option value="{{ $optionKey }}" {{ $selected }}>{{ ucfirst($optionValue) }}</option>
        <?php } ?>
    </select>
</div>