<?php 
    $method = $field['selector'];                                                    
    $options = $controller::$method(@$item, @$id);
?>
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <select name="{{ $field['field'] }}[]" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} multiple="true" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
        <?php foreach($options['all'] as $optionKey => $optionValue) { ?>
            <option style="padding:5px" value="{{ $optionKey }}" <?php echo array_key_exists($optionKey, $options['selected']) ? 'selected' : ''; ?>>{{ ucfirst($optionValue) }}</option>
        <?php } ?>
    </select>
</div>