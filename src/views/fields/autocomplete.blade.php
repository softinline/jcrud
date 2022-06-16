<?php
    $value = @$item->{$field['field']};
    if($item) {
        $method = $field['autocompleteFunction'];
        $value = $controller::$method(@$item, @$id);
    }
?>
<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }} ({{ trans('messages.start_writing_something') }})</label>
    <input type="email" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ $value }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
    <input type="hidden" name="{{ $field['field'] }}_autocomplete" id="{{ $field['field'] }}_autocomplete" value="{{ @$item->{$field['field']} }}"/>
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