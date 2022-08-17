<?php
    $params = \Request::all();
?>
<?php foreach($params as $kParam => $vParam) { ?>
    <?php        
        $queryString = http_build_query(\Request::except($kParam));
        if($queryString != '') {
            $queryString = '?'.$queryString;
        }
    ?>
    <div class="crud-fast-filter-element">
        {{ $kParam }} = {{ $vParam }} <a href="{{ \Request::root().'/'.$ajax.\Request::path().$queryString }}">[x]</a>
    </div>
<?php } ?>