<div class="form-group">                                                    
    <input type="checkbox" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ $field['required'] ? 'jcrud-frm-item-required' : '' }}" {{ @$field['disabled'] ? 'disabled' : '' }} value="1" <?php echo @$item->{$field['field']} === 1 ? 'checked' : ''; ?> {{ $field['required'] ? 'required' : '' }} jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }} {{ $field['required'] ? '*' : '' }}</label>
</div>