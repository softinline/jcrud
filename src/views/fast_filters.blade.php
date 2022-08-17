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
        {{ $kParam }} = {{ $vParam }} <a href="{{ url()->current().$queryString }}">[x]</a>
    </div>
<?php } ?>