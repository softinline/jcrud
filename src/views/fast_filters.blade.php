<?php
    $params = \Request::all();
    $counter = count($params);
?>
<div class="row">
    <?php foreach($params as $kParam => $vParam) { ?>
        <?php if($kParam != 'jcrudSubList') { ?>
            <?php        
                $queryString = http_build_query(\Request::except($kParam));
                if($queryString != '') {
                    $queryString = '?'.$queryString;
                }
            ?>
            <div class="col-lg-{{ (12 / $counter) }}">
                <div class="crud-fast-filter-element">
                    {{ $kParam }} = {{ $vParam }} <a href="{{ \Request::root().'/'.$ajax.\Request::path().$queryString }}">[x]</a>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>