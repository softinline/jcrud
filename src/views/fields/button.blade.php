<div class="form-group">
    <button type="button" class="btn btn-primary {{ @$config['btnStyles'] }}" onclick="{{ $field['action'] }}">{{ ucfirst(trans('messages.'.$field['title'])) }}</button>
</div>