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