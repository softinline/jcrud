<?php 
    $method = $field['selector'];                                                    
    $options = $controller::$method(@$item, @$id);
?>
<div class="form-group">    
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] == 'required' ? '*' : '' }}</label>
    <?php if(@$field['searchBar']) { ?>
        <input type="text" class="form-control mb-2" name="search-bar-{{ $field['field'] }}" id="search-bar-{{ $field['field'] }}" placeholder="{{ ucfirst(trans('search')) }}">
    <?php } ?>
    <?php foreach($options['all'] as $optionKey => $optionValue) { ?>
        <div class="option-search-bar-{{ $field['field'] }}" data-title="{{ mb_strtolower($optionValue) }}">
            <input type="checkbox" name="{{ $field['field'] }}[]" id="{{ $field['field'] }}-{{ $optionKey }}" style="padding:5px" value="{{ $optionKey }}" <?php echo array_key_exists($optionKey, $options['selected']) ? 'checked' : ''; ?> jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}"> {{ ucfirst($optionValue) }}
        </div>
    <?php } ?>
</div>
<script>
    $('#search-bar-{{ $field['field'] }}').keyup(function() {
        var value = $(this).val().toLowerCase();
        if(value != '') {
            console.log(value);
            $(".option-search-bar-{{ $field['field'] }}").hide();            
            $(".option-search-bar-{{ $field['field'] }}[data-title*='"+value+"']").show();
        }
        else {
            $(".option-search-bar-{{ $field['field'] }}").show();        
        }
    });
</script>