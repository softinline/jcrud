<select class="form-control" id="jcrud-sublist" name="jcrud-sublist">
    <option value="">{{ ucfirst(trans('messages.select-one-list')) }}</option>
    <?php foreach($list['subLists'] as $sublist) { ?>
        <option value="{{ $sublist['value'] }}" <?php echo \Request::get('jcrudSubList') == $sublist['value'] ? 'selected' : ''; ?>>{{ ucfirst(trans('messages.'.$sublist['title'])) }}</option>
    <?php } ?>
</select>
<br />
<script>
    $("#jcrud-sublist").on('change', function() {
        var url = crud.updateQueryStringParameter(window.location.toString(),'jcrudSubList',$(this).val());                
        window.location = url;        
    });
</script>
