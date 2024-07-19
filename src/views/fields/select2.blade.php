<?php 

    // options
    $method = $field['selector'];
    $options = $controller::$method(@$item, @$id);

    // field
    $fieldString = $field['field'];

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

    // class
    $class = '';
    if(isset($field['class'])) {
        $class = $field['class'];
    }

    // show condition
    $show = true;
    if(array_key_exists('beforeShow', $field)) {
        $method = $field['beforeShow'];
        $show = $controller::$method(@$item);
    }

?>
<?php if($show) { ?>
    <div class="form-group">
        <label>{{ ucfirst(trans('messages.'.$title)) }}: {{ $required ? '*' : '' }}</label>
        <select name="{{ $fieldString }}" id="{{ $fieldString }}" class="form-control jcrud-select2 {{ $class }} {{ $required ? 'jcrud-frm-item-required' : '' }}" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }} jcrud-data-title="{{ ucfirst(trans('messages.'.$title)) }}">
            <option value="">{{ ucfirst(trans('messages.select-option')) }}</option>

            <?php foreach($options as $option) { ?>
            <?php if($option['title']) { ?> <optgroup label="{{$option['title']}}"> <?php } ?>
                <?php foreach($option['options'] as $optionKey => $optionValue) { ?>
                    <?php
                        $selected = '';  
                        // determine default value
                        // if edit, dont take effect
                        // on add check if Request has a param with this value    
                        if(@$item) {
                            $selected = $optionKey == @$item->{$fieldString} ? 'selected' : '';
                        }
                        else {
                            $selected = $optionKey == \Request::get($fieldString) ? 'selected' : '';
                        }
                    ?>
                    <option value="{{ $optionKey }}" {{ $selected }}>{{ ucfirst($optionValue) }}</option>
                <?php } ?>
                
            <?php if($option['title']) { ?> </optgroup> <?php } ?>
            <?php } ?>

        </select>
    </div>
    <script>
        $(document).ready(function() {
            $('.jcrud-select2').select2();
        });
    </script>
<?php } ?>