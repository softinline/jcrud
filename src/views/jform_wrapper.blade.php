@extends($config['layout'])
@section('content')
    <div class="container mt-5 mb-5">
        <div class="row mt-2">
            <div class="col-lg-6">
                <div class="page-title">
                    <h4>{{ ucfirst(trans('messages.'.$config['title'])) }}</h4>
                </div>   
            </div>                
            <div class="col-lg-6 text-right">
                <div class="breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <?php if($breadcrumb) { ?>
                                <?php echo $breadcrumb; ?>
                            <?php } else { ?>
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}">Dashboard</a>
                                </li>                            
                                <?php if(array_key_exists('url_parent', $config)) { ?>
                                    <li class="breadcrumb-item">
                                        <a href="{{ url($config['url_parent']) }}">{{ ucfirst(trans('messages.'.$config['title_parent'])) }}</a>
                                    </li>
                                <?php } ?>
                                <?php if(array_key_exists('url', $config)) { ?>
                                    <li class="breadcrumb-item">
                                        <a href="{{ url($config['url']) }}">{{ ucfirst(trans('messages.'.$config['title'])) }}</a>
                                    </li>
                                <?php } ?>
                                <li class="breadcrumb-item active">
                                    <?php if($item) { ?>                                        
                                        <?php if(array_key_exists('field_title', $form)) { ?>
                                            {{ $item->{$form['field_title']} }}
                                        <?php } ?>
                                    <?php } else { ?>
                                        {{ ucfirst(trans('messages.add')) }}
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        @yield('body')
    </div>
@stop