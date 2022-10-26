<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <input type="password" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'jcrud-frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
</div>