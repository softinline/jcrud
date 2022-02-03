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
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="file" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} value="{{ @$item->{$field['field']} }}">
        </div>
        <?php if(array_key_exists('show', $field)) { ?>
            <?php
                $method = $field['show'];
                $show = $controller::$method(@$item, @$id);
            ?>
            <?php echo $show; ?>
        <?php } ?>
    <?php } ?>                                            
    <?php if($field['type'] == 'date') { ?>
        <!-- date -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ @$field['class'] != '' ?  $field['class'] : 'date-picker' }} form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} != '' ? $item->{$field['field']}->format("d/m/Y") : '' }}" autocomplete="off">
        </div>
    <?php } ?>
    <?php if($field['type'] == 'datetime') { ?>
        <!-- datetime -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ @$field['class'] != '' ?  $field['class'] : 'date-picker' }} form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} != '' ? $item->{$field['field']}->format("d/m/Y H:i") : '' }}">
        </div>
    <?php } ?>
    <?php if($field['type'] == 'text') { ?>
        <!-- text -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} }}">
        </div>
    <?php } ?>
    <?php if($field['type'] == 'number') { ?>
        <!-- number -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="number" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} }}">
        </div>
    <?php } ?>        
    <?php if($field['type'] == 'password') { ?>
        <!-- password -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="password" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }}>
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'email') { ?>
        <!-- email -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>                                
            <input type="email" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} }}">
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'button') { ?>
        <!-- button -->
        <div class="form-group">
            <button type="button" class="btn btn-primary btn-sm" onclick="{{ $field['action'] }}">{{ ucfirst(trans('messages.'.$field['title'])) }}</button>
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'checkbox') { ?>
        <!-- checkbox -->
        <div class="form-group">                                                    
            <input type="checkbox" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ $field['required'] ? 'frm-item-required' : '' }}" {{ @$field['disabled'] ? 'disabled' : '' }} value="1" <?php echo @$item->{$field['field']} === 1 ? 'checked' : ''; ?> {{ $field['required'] ? 'required' : '' }}>
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }} {{ $field['required'] ? '*' : '' }}</label>
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'textarea') { ?>
        <!-- textarea -->
        <div class="form-group">                                                                                                        
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }} {{ $field['required'] ? '*' : '' }}</label>
            <textarea name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} rows="{{ @$field['rows'] }}">{{ @$item->{$field['field']} }}</textarea>
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'editor') { ?>
        <!-- editor -->            
        <div class="form-group">                                                                                                        
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }} {{ $field['required'] ? '*' : '' }}</label>
            <textarea name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }}><?php echo @$item->{$field['field']}; ?></textarea>
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'select') { ?>
        <!-- select -->
        <?php 
            $method = $field['selector'];            
            $options = $controller::$method(@$item, @$id);
        ?>
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <select name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }}>
                <option value="">{{ ucfirst(trans('messages.select-option')) }}</option>
                <?php foreach($options as $optionKey => $optionValue) { ?>
                    <option value="{{ $optionKey }}" <?php echo $optionKey ==  @$item->{$field['field']} ? 'selected' : ''; ?>>{{ ucfirst($optionValue) }}</option>
                <?php } ?>
            </select>
        </div>
    <?php } ?>
    <?php if($field['type'] == 'selectPopUp') { ?>
        <!-- selectPopUp -->
        <?php 
            $method = $field['selector'];            
            $options = $controller::$method(@$item, @$id);
        ?>
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <select name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }}>
                <option value="">{{ ucfirst(trans('messages.select-option')) }}</option>
                <?php foreach($options as $optionKey => $optionValue) { ?>
                    <option value="{{ $optionKey }}" <?php echo $optionKey ==  @$item->{$field['field']} ? 'selected' : ''; ?>>{{ ucfirst($optionValue) }}</option>
                <?php } ?>
            </select>
            <button type="button" class="btn btn-primary mt-2" onclick="$('#modal-{{ $field['field'] }}').modal()">{{ ucfirst(trans('messages.options')) }}</button>
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
                            <div class="row selectable-row <?php echo $optionKey ==  @$item->{$field['field']} ? 'selectable-row-selected' : ''; ?>" id="selectable-row-{{ $optionKey }}" onclick="selectPopUpOption('{{ $field['field'] }}', '{{ $optionKey }}'); $('#modal-{{ $field['field'] }}').modal('close')">                                
                                <div class="col-lg-12">
                                    {{ ucfirst($optionValue) }}
                                    <br /><small>{{ $optionKey }}</small>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ ucfirst(trans('messages.accept')) }}</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if($field['type'] == 'select-multiple') { ?>
        <!-- select multiple -->
        <?php 
            $method = $field['selector'];                                                    
            $options = $controller::$method(@$item, @$id);
        ?>
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <select name="{{ $field['field'] }}[]" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} multiple="true">
                <?php foreach($options['all'] as $optionKey => $optionValue) { ?>
                    <option style="padding:5px" value="{{ $optionKey }}" <?php echo array_key_exists($optionKey, $options['selected']) ? 'selected' : ''; ?>>{{ ucfirst($optionValue) }}</option>
                <?php } ?>
            </select>
        </div>
    <?php } ?>
    <?php if($field['type'] == 'checkbox-multiple') { ?>
        <!-- select multiple -->
        <?php 
            $method = $field['selector'];                                                    
            $options = $controller::$method(@$item, @$id);
        ?>
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] == 'required' ? '*' : '' }}</label>
            <?php foreach($options['all'] as $optionKey => $optionValue) { ?>
                <br /><input type="checkbox" name="{{ $field['field'] }}[]" id="{{ $field['field'] }}" style="padding:5px" value="{{ $optionKey }}" <?php echo array_key_exists($optionKey, $options['selected']) ? 'checked' : ''; ?>> {{ ucfirst($optionValue) }}
            <?php } ?>
        </div>
    <?php } ?>
    <?php if($field['type'] == 'json') { ?>
        <!-- json -->
        <?php
            $json = json_decode(@$item->{$field['field']}, true);
        ?>
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <div><pre><?php echo print_r($json, true); ?></pre></div>                                                
        </div>
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
            </script>
            <?php 
                // check if display
                $display = @$item->{$field['field']} == $children['value'] ? 'block' : 'none'; 
            ?>
            <div id="div-{{ $field['field'] }}-{{ $children['value'] }}" style="display:{{ $display }}" class="softinline-jcrud-childrens-div">
                <?php foreach($children['fields'] as $fieldChildren) { ?>                
                    @include('softinline::fields', [
                        'field' => $fieldChildren,
                        'controller' => $controller,
                        'config' => $config,
                        'id' => $id,
                    ])
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
<?php } ?>