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
                    <a class="nav-link" href="#tab_{{ $field['field'] }}_{{ $language->iso }}" data-toggle="tab" aria-expanded="false" id="li-text-area">
                        {{ ucfirst($language->language) }} {{ $field['translationsRequired'] ? '*' : '' }}
                    </a>
                </li>
            @endforeach
        </ul>    
        <div class="tab-content">
            <div class="tab-pane active" id="tab_{{ $field['field'] }}_default">
                <div class="form-group">                            
                    <textarea name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'jcrud-frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} rows="{{ @$field['rows'] }}" jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">{{ @$item->{$field['field']} }}</textarea>
                </div>
            </div>
            <?php                                            
                if($item) {
                    $method = $field['translations'];
                    $translations = $controller::$method($field['field'], @$item, @$item->id);
                }
            ?>
            @foreach($languages as $language)
                <div class="tab-pane" id="tab_{{ $field['field'] }}_{{ $language->iso }}">
                    <div class="form-group">                                
                        <textarea name="{{ $field['field'] }}_{{ $language->iso }}" id="{{ $field['field'] }}_{{ $language->iso }}" class="form-control {{ $field['translationsRequired'] ? 'jcrud-frm-item-required' : '' }}" {{ $field['translationsRequired'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} rows="{{ @$field['rows'] }}" jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}  ({{ $language->iso }})">{{ @$translations[$language->iso] }}</textarea>
                    </div>
                </div>
            @endforeach                    
        </div>     
    </div>
<?php } else { ?>
    <div class="form-group">
        <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
        <textarea name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="form-control {{ $field['required'] ? 'jcrud-frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} {{ @$field['disabled'] ? 'disabled' : '' }} rows="{{ @$field['rows'] }}" jcrud-data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}">{{ @$item->{$field['field']} }}</textarea>
    </div>
<?php } ?>