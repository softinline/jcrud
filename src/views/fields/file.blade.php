<!-- file -->
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <input type="file" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ $field['required'] ? 'jcrud-frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} value="{{ @$item->{$field['field']} }}" jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
</div>
<?php if(array_key_exists('show', $field)) { ?>
    <?php
        $method = $field['show'];
        $show = $controller::$method(@$item, @$id);
    ?>
    <?php echo $show; ?>
<?php } ?>