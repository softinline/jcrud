<div class="form-group">
    <label>{{ ucfirst(trans('messages.'.$field['title'])) }}: {{ $field['required'] ? '*' : '' }}</label>
    <input type="file" name="{{ $field['field'] }}" id="{{ $field['field'] }}" class="{{ $field['required'] ? 'frm-item-required' : '' }}" {{ $field['required'] ? 'required' : '' }} value="{{ @$item->{$field['field']} }}" data-title="{{ ucfirst(trans('messages.'.$field['title'])) }}"  accept="image/*">
    <br />
    <?php if(array_key_exists('show', $field)) { ?>
    <?php
        $method = $field['show'];
        $show = $controller::$method(@$item, @$id);
    ?>
    <?php echo $show; ?>
    <?php } ?>
    <br />
    <canvas id="canvas-{{ $field['field'] }}" style="width:{{ $field['width'] }}px; height:auto;"></canvas>
</div>                
<script>
    $(function() {
        
        var input = document.querySelector('input[id={{ $field['field'] }}]');
        input.onchange = function () {
            var file = input.files[0];
            drawOnCanvas(file);
        };
    
        function drawOnCanvas(file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var dataURL = e.target.result,
                c = document.querySelector("#canvas-{{ $field['field'] }}"),
                ctx = c.getContext('2d'),
                img = new Image();

                img.onload = function() {                        
                    c.width = img.width;
                    c.height = img.height;
                    ctx.drawImage(img, 0, 0);
                };

                img.src = dataURL;
            };
            reader.readAsDataURL(file);
        }

    });
</script>