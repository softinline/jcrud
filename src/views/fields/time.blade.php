<?php

    // field
    $ffield = $field['field'];

    // title
    $title = $field['title'];

    // required
    $required = false;
    if(isset($field['required'])) {
        $required = $field['required'];
    }

    // disabled
    $disabled = false;
    if(isset($field['disabled'])) {
        $disabled = $field['disabled'];
    }

    // format
    $format = "d/m/Y";
    if(isset($field['format'])) {
        $format = $field['format'];
    }

    // autocomplete
    $autocomplete = "off";

    // class    
    $class = '';
    if(isset($field['class'])) {
        $class = $field['class'];
    }
    
?>
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$title)) }}: {{ $required ? '*' : '' }}</label>
    <input type="text" name="{{ $ffield }}" id="{{ $ffield }}" class="form-control time-picker {{ $class }} {{ $required ? 'jcrud-frm-item-required' : '' }}" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }} value="{{ @$item->{$ffield} != '' ? $item->{$ffield}->format($format) : '' }}" autocomplete="{{ $autocomplete }}" jcrud-data-title="{{ ucfirst(trans('messages.'.$title)) }}">
</div>