<div class="form-group">                                                    
    <input type="checkbox" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ $field['required'] ? 'frm-item-required' : '' }}" {{ @$field['disabled'] ? 'disabled' : '' }} value="1" <?php echo @$item->{$field['field']} === 1 ? 'checked' : ''; ?> {{ $field['required'] ? 'required' : '' }} data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }} {{ $field['required'] ? '*' : '' }}</label>
</div>