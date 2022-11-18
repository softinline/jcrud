<ul class="nav nav-tabs">
    <?php $first = true; ?>
    <?php foreach($form['tabs'] as $tab) { ?>
        <?php
            $show = true;
            if(array_key_exists('condition', $tab)) {
                $method = $tab['condition'];
                $show = $controller::$method(@$item);
            }
        ?>
        <?php if($show) { ?>
            <li class="nav-item">
                <a class="nav-link <?php echo $first ? 'active' : ''; ?>" href="#tab_{{ $tab['key'] }}" data-toggle="tab" aria-expanded="false">{{ ucfirst(trans('messages.'.$tab['title'])) }}</a>
            </li>
            <?php $first = false; ?>
        <?php } ?>
    <?php } ?>
</ul>
<div class="tab-content">
    <?php $first = true; ?>
    <?php foreach($form['tabs'] as $tab) { ?>                
        <?php
            $show = true;
            if(array_key_exists('condition', $tab)) {
                $method = $tab['condition'];
                $show = $controller::$method(@$item);
            }
        ?>
        <?php if($show) { ?>
            <div class="tab-pane <?php echo $first ? 'active' : ''; ?>" id="tab_{{ $tab['key'] }}">
                @include('softinline::jform-content', [
                    'tab' => $tab,
                    'config' => $config,
                    'form' => $form,
                    'item' => $item,
                ])                
            </div>
            <?php $first = false; ?>
        <?php } ?>
    <?php } ?>
</div>