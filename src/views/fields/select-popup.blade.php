<?php 
    $method = $field['selector'];            
    $options = $controller::$method(@$item, @$id);
?>
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <select name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
        <option value="">{{ ucfirst(trans('messages.select-option')) }}</option>
        <?php foreach($options as $optionKey => $optionValue) { ?>
            <option value="{{ $optionKey }}" <?php echo $optionKey ==  @$item->{$field['field']} ? 'selected' : ''; ?>>{{ ucfirst($optionValue) }}</option>
        <?php } ?>
    </select>
    <button type="button" class="btn btn-primary {{ @$config['btnStyles'] }} mt-2" onclick="$('#modal-{{ $field['field'] }}').modal()">{{ ucfirst(trans('messages.options')) }}</button>
</div>
<div class="modal" tabindex="-1" role="dialog" id="modal-{{ $field['field'] }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ ucfirst(trans('messages.select-option')) }} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                        
                <?php foreach($options as $optionKey => $optionValue) { ?>                            
                    <div class="row selectable-row <?php echo $optionKey ==  @$item->{$field['field']} ? 'selectable-row-selected' : ''; ?>" id="selectable-row-{{ $optionKey }}" onclick="crud.selectPopUpOption('{{ $field['field'] }}', '{{ $optionKey }}'); modals.close('#modal-{{ $field['field'] }}')">
                        <div class="col-lg-12">
                            {{ ucfirst($optionValue) }}
                            <br /><small>{{ $optionKey }}</small>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer">                        
                <button type="button" class="btn btn-secondary {{ @$config['btnStyles'] }}" data-dismiss="modal">{{ ucfirst(trans('messages.accept')) }}</button>
            </div>
        </div>
    </div>
</div>