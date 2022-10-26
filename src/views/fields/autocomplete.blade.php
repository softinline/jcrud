<?php
    $value = "";
    $hidden = "";
    // has item, then show value in db
    if($item != null) {        
        $method = $field['autocompleteFunction'];
        $value = $controller::$method(@$item, @$id);
        $hidden = @$item->{$field['field']};
    }
    // no has item, check if we want a default value
    else {        
        if(\Request::get($field['field']) != '') {
            $method = $field['autocompleteFunction'];            
            $value = $controller::$method(@$item, @$id, \Request::get($field['field']));
            $hidden = \Request::get($field['field']);
        }
    }    
?>
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }} ({{ trans('messages.start_writing_something') }})</label>
    <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'jcrud-frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ $value }}" jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
    <input type="hidden" name="{{ $field['field'] }}_autocomplete" id="{{ $field['field'] }}_autocomplete" value="{{ $hidden }}"/>
    <script>
        $(function() {
            $("#{{ $field['field'] }}").autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: "{{ $field['autocompleteUrl'] }}",
                        dataType: "jsonp",
                        data: {
                            term:request.term                                
                        },
                        success: function(data) {
                            response(data);
                        },
                        complete: function() {                                
                        }
                    });
                },
                minLength: 2,
                select: function( event, ui ) {
                    $("#{{ $field['field'] }}_autocomplete").val(ui.item.id);
                }
            });
        });
    </script>
</div>