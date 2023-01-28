<?php
    $process = true;
    if(array_key_exists('condition', $field)) {
        $method = $field['condition'];
        $process = $controller::$method(@$item, @$id);            
    }
?>
<?php if($process) { ?>
    <?php if($field['type'] == 'custom') { ?>
        <!-- custom -->
        <?php            
            $method = $field['custom'];
            $response = $controller::$method(@$item, @$id);
            echo $response;
        ?>
    <?php } ?>
    <?php if($field['type'] == 'file') { ?>
        <!-- file -->
        @include('softinline::fields.file', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'color') { ?>
        <!-- color -->
        @include('softinline::fields.color', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'date') { ?>
        <!-- date -->
        @include('softinline::fields.date', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'datetime') { ?>
        <!-- datetime -->
        @include('softinline::fields.datetime', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'text') { ?>        
        <!-- text -->
        @include('softinline::fields.text', [
            'item' => @$item,
            'field' => $field,
            'config' => $config,
            'languages' => $languages
        ])
    <?php } ?>
    <?php if($field['type'] == 'number') { ?>
        <!-- number -->
        @include('softinline::fields.number', [
            'item' => @$item,
            'field' => $field,
            'config' => $config            
        ])
    <?php } ?>        
    <?php if($field['type'] == 'password') { ?>
        <!-- password -->
        @include('softinline::fields.password', [
            'item' => @$item,
            'field' => $field,
            'config' => $config            
        ])
    <?php } ?>                                            
    <?php if($field['type'] == 'email') { ?>
        <!-- email -->
        @include('softinline::fields.email', [
            'item' => @$item,
            'field' => $field,
            'config' => $config            
        ])
    <?php } ?>                                            
    <?php if($field['type'] == 'button') { ?>
        <!-- button -->
        @include('softinline::fields.button', [
            'item' => @$item,
            'field' => $field,
            'config' => $config            
        ])
    <?php } ?>                                            
    <?php if($field['type'] == 'checkbox') { ?>
        <!-- checkbox -->
        @include('softinline::fields.checkbox', [
            'item' => @$item,
            'field' => $field,
            'config' => $config            
        ])
    <?php } ?>                                            
    <?php if($field['type'] == 'textarea' || $field['type'] == 'editor') { ?>
        <!-- textarea / editor ck-->
        @include('softinline::fields.textarea', [
            'item' => @$item,
            'field' => $field,
            'config' => $config,
            'languages' => $languages
        ])
    <?php } ?>
    <?php if($field['type'] == 'select') { ?>
        <!-- select -->
        @include('softinline::fields.select', [
            'item' => @$item,
            'field' => $field,
            'config' => $config,
            'languages' => $languages
        ])
    <?php } ?>
    <?php if($field['type'] == 'select-popup') { ?>
        <!-- select popup -->
        @include('softinline::fields.select-popup', [
            'item' => @$item,
            'field' => $field,
            'config' => $config            
        ])
    <?php } ?>
    <?php if($field['type'] == 'select-multiple') { ?>
        <!-- select multiple -->
        @include('softinline::fields.select-multiple', [
            'item' => @$item,
            'field' => $field,
            'config' => $config            
        ])
    <?php } ?>
    <?php if($field['type'] == 'checkbox-multiple') { ?>
        <!-- checkbox multiple -->
        @include('softinline::fields.checkbox-multiple', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'json') { ?>
        <!-- json -->
        @include('softinline::fields.json', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'autocomplete') { ?>
        <!-- autocomplete -->
        @include('softinline::fields.autocomplete', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'image') { ?>
        <!-- image -->
        @include('softinline::fields.image', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'camera') { ?>
        <!-- camera -->
        @include('softinline::fields.camera', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'mapbox') { ?>
        <!-- mapbox -->
        @include('softinline::fields.mapbox', [
            'item' => @$item,
            'field' => $field,
            'config' => $config
        ])
    <?php } ?>
    <?php if($field['type'] == 'view') { ?>
        <!-- view -->
        @include($field['view'], [
            'item' => @$item,
            'field' => $field,
            'config' => $config,
        ])
    <?php } ?>
    <?php if(array_key_exists('childrens', $field)) { ?>
        <?php foreach($field['childrens'] as $children) { ?>
            <script>
                $(function() {
                    $("#{{ $field['field'] }}").on('change', function() {                
                        <?php if($field['type'] == 'checkbox') { ?>
                            var value = $('input[name="{{ $field['field'] }}"]:checked').length > 0;
                        <?php } else { ?>
                            var value = $("#{{ $field['field'] }}").val();
                        <?php } ?>                
                        $("#div-{{ $field['field'] }}-{{ $children['value'] }}").hide();
                        if(value == '{{ $children['value'] }}') {
                            $("#div-{{ $field['field'] }}-{{ $children['value'] }}").toggle('slow');
                        }
                    });
                });
            </script>
            <?php
                // check if display
                $display = @$item->{$field['field']} == $children['value'] ? 'block' : 'none'; 
            ?>
            <div id="div-{{ $field['field'] }}-{{ $children['value'] }}" style="display:{{ $display }}" class="jcrud-childrens-div">
                <?php foreach($children['fields'] as $fieldChildren) { ?>                
                    @include('softinline::fields', [
                        'field' => $fieldChildren,
                        'controller' => $controller,
                        'config' => $config,
                        'id' => $id,
                        'fromParent' => true,
                    ])
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>