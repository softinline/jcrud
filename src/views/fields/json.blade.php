<?php
    $json = json_decode(@$item->{$field['field']}, true);
?>
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <div><pre><?php echo print_r($json, true); ?></pre></div>                                                
</div>