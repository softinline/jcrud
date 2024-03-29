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
                                    <a href="{{ url('/') }}">{{ ucfirst(trans('messages.dashboard')) }}</a>
                                </li>
                                <?php if(array_key_exists('url', $config)) { ?>
                                    <li class="breadcrumb-item">
                                        <a href="{{ url($config['url']) }}">{{ ucfirst(trans('messages.'.$config['title'])) }}</a>
                                    </li>
                                <?php } ?>
                                <li class="breadcrumb-item active">
                                    <?php if($item) { ?>
                                        <?php if(array_key_exists('fieldTitle', $form)) { ?>
                                            {{ $item->{$form['fieldTitle']} }}
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php if(array_key_exists('title', $form)) { ?>
                                            {{ ucfirst(trans('messages.'.$form['title'])) }}
                                        <?php } else { ?>
                                            {{ ucfirst(trans('messages.add')) }}
                                        <?php } ?>
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