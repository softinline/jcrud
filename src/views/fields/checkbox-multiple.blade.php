<?php 
    $method = $field['selector'];                                                    
    $options = $controller::$method(@$item, @$id);
?>
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] == 'required' ? '*' : '' }}</label>
    <?php foreach($options['all'] as $optionKey => $optionValue) { ?>
        <br /><input type="checkbox" name="{{ $field['field'] }}[]" id="{{ $field['field'] }}" style="padding:5px" value="{{ $optionKey }}" <?php echo array_key_exists($optionKey, $options['selected']) ? 'checked' : ''; ?> data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}"> {{ ucfirst($optionValue) }}
    <?php } ?>
</div>