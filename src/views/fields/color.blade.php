<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'jcrud-frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} }}" jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
</div>