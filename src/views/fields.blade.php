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
            <input type="file" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} value="{{ @$item->{$field['field']} }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
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
            <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ @$field['class'] != '' ?  $field['class'] : 'date-picker' }} form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} != '' ? $item->{$field['field']}->format("d/m/Y") : '' }}" autocomplete="off" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
        </div>
    <?php } ?>
    <?php if($field['type'] == 'datetime') { ?>
        <!-- datetime -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ @$field['class'] != '' ?  $field['class'] : 'date-picker' }} form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} != '' ? $item->{$field['field']}->format("d/m/Y H:i") : '' }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
        </div>
    <?php } ?>
    <?php if($field['type'] == 'text') { ?>        
        <!-- text -->
        <?php if(@$field['translate']) { ?>
            <div class="form-group">
                <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab_{{ $field['field'] }}_default" data-toggle="tab" aria-expanded="false" id="li-text-area">                
                            Default {{ $field['required'] ? '*' : '' }}
                        </a>
                    </li>
                    @foreach($languages as $language)
                        <li>
                            <a class="nav-link" href="#tab_{{ $field['field'] }}_{{ $language->id }}" data-toggle="tab" aria-expanded="false" id="li-text-area">
                                {{ ucfirst($language->language) }} {{ $field['translationsRequired'] ? '*' : '' }}
                            </a>
                        </li>
                    @endforeach
                </ul>    
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_{{ $field['field'] }}_default">
                        <div class="form-group">
                            <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
                        </div>
                    </div>
                    <?php                                            
                        if($item) {
                            $method = $field['translations'];
                            $translations = $controller::$method($field['field'], @$item, @$item->id);
                        }
                    ?>                    
                    @foreach($languages as $language)
                        <div class="tab-pane" id="tab_{{ $field['field'] }}_{{ $language->id }}">
                            <div class="form-group">                                
                                <input type="text" name="{{ $field['field'] }}_{{ $language->id }}" id="{{ $field['field'] }}_{{ $language->id }}" class="form-control {{ $field['translationsRequired'] ? 'frm-item-required' : '' }}" {{ $field['translationsRequired'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ $translations[$language->id] }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }} ({{ $language->id }})">
                            </div>
                        </div>
                    @endforeach                    
                </div>     
            </div>
        <?php } else { ?>
            <div class="form-group">
                <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
                <input type="text" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
            </div>
        <?php } ?>
    <?php } ?>
    <?php if($field['type'] == 'number') { ?>
        <!-- number -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="number" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
        </div>
    <?php } ?>        
    <?php if($field['type'] == 'password') { ?>
        <!-- password -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <input type="password" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'email') { ?>
        <!-- email -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>                                
            <input type="email" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ @$item->{$field['field']} }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'button') { ?>
        <!-- button -->
        <div class="form-group">
            <button type="button" class="btn btn-primary {{ @$config['btnStyles'] }}" onclick="{{ $field['action'] }}">{{ ucfirst(trans('messages.'.$field['title'])) }}</button>
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'checkbox') { ?>
        <!-- checkbox -->
        <div class="form-group">                                                    
            <input type="checkbox" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ $field['required'] ? 'frm-item-required' : '' }}" {{ @$field['disabled'] ? 'disabled' : '' }} value="1" <?php echo @$item->{$field['field']} === 1 ? 'checked' : ''; ?> {{ $field['required'] ? 'required' : '' }} data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }} {{ $field['required'] ? '*' : '' }}</label>
        </div>
    <?php } ?>                                            
    <?php if($field['type'] == 'textarea' || $field['type'] == 'editor') { ?>
        <!-- textarea / editor ck-->
        <?php if(@$field['translate']) { ?>
            <div class="form-group">
                <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab_{{ $field['field'] }}_default" data-toggle="tab" aria-expanded="false" id="li-text-area">                
                            Default {{ $field['required'] ? '*' : '' }}
                        </a>
                    </li>
                    @foreach($languages as $language)
                        <li>
                            <a class="nav-link" href="#tab_{{ $field['field'] }}_{{ $language->id }}" data-toggle="tab" aria-expanded="false" id="li-text-area">
                                {{ ucfirst($language->language) }} {{ $field['translationsRequired'] ? '*' : '' }}
                            </a>
                        </li>
                    @endforeach
                </ul>    
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_{{ $field['field'] }}_default">
                        <div class="form-group">                            
                            <textarea name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} rows="{{ @$field['rows'] }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">{{ @$item->{$field['field']} }}</textarea>
                        </div>
                    </div>
                    <?php                                            
                        if($item) {
                            $method = $field['translations'];
                            $translations = $controller::$method($field['field'], @$item, @$item->id);
                        }
                    ?>
                    @foreach($languages as $language)
                        <div class="tab-pane" id="tab_{{ $field['field'] }}_{{ $language->id }}">
                            <div class="form-group">                                
                                <textarea name="{{ $field['field'] }}_{{ $language->id }}" id="{{ $field['field'] }}_{{ $language->id }}" class="form-control {{ $field['translationsRequired'] ? 'frm-item-required' : '' }}" {{ $field['translationsRequired'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} rows="{{ @$field['rows'] }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}  ({{ $language->id }})">{{ @$translations[$language->id] }}</textarea>
                            </div>
                        </div>
                    @endforeach                    
                </div>     
            </div>
        <?php } else { ?>
            <div class="form-group">
                <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
                <textarea name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} rows="{{ @$field['rows'] }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">{{ @$item->{$field['field']} }}</textarea>
            </div>
        <?php } ?>        
    <?php } ?>
    <?php if($field['type'] == 'select') { ?>
        <!-- select -->
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
                            <div class="row selectable-row <?php echo $optionKey ==  @$item->{$field['field']} ? 'selectable-row-selected' : ''; ?>" id="selectable-row-{{ $optionKey }}" onclick="selectPopUpOption('{{ $field['field'] }}', '{{ $optionKey }}'); $('#modal-{{ $field['field'] }}').modal('close')">                                
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
    <?php } ?>
    <?php if($field['type'] == 'select-multiple') { ?>
        <!-- select multiple -->
        <?php 
            $method = $field['selector'];                                                    
            $options = $controller::$method(@$item, @$id);
        ?>
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
            <select name="{{ $field['field'] }}[]" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} multiple="true" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
                <?php foreach($options['all'] as $optionKey => $optionValue) { ?>
                    <option style="padding:5px" value="{{ $optionKey }}" <?php echo array_key_exists($optionKey, $options['selected']) ? 'selected' : ''; ?>>{{ ucfirst($optionValue) }}</option>
                <?php } ?>
            </select>
        </div>
    <?php } ?>
    <?php if($field['type'] == 'checkbox-multiple') { ?>
        <!-- checkbox multiple -->
        <?php 
            $method = $field['selector'];                                                    
            $options = $controller::$method(@$item, @$id);
        ?>
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] == 'required' ? '*' : '' }}</label>
            <?php foreach($options['all'] as $optionKey => $optionValue) { ?>
                <br /><input type="checkbox" name="{{ $field['field'] }}[]" id="{{ $field['field'] }}" style="padding:5px" value="{{ $optionKey }}" <?php echo array_key_exists($optionKey, $options['selected']) ? 'checked' : ''; ?> data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}"> {{ ucfirst($optionValue) }}
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
    <?php if($field['type'] == 'autocomplete') { ?>
        <!-- checkbox multiple -->
        <?php
            $value = @$item->{$field['field']};
            if($item) {
                $method = $field['autocompleteFunction'];
                $value = $controller::$method(@$item, @$id);
            }
        ?>
        <!-- autocomplete -->
        <div class="form-group">
            <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }} ({{ trans('messages.start_writing_something') }})</label>
            <input type="email" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} value="{{ $value }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">
            <input type="hidden" name="{{ $field['field'] }}_autocomplete" id="{{ $field['field'] }}_autocomplete" value="{{ @$item->{$field['field']} }}"/>
            <script>
                $("#{{ $field['field'] }}").autocomplete({
                    source: function( request, response ) {
                        $.ajax({
                            url: "{{ $field['autocompleteUrl'] }}",
                            dataType: "jsonp",
                            data: {
                                term: request.term                                
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
            </script>
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