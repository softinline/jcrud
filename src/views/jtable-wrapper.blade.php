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
                                <?php if(array_key_exists('url_parent', $config)) { ?>
                                    <li class="breadcrumb-item">
                                        <a href="{{ url($config['url_parent']) }}">{{ ucfirst(trans('messages.'.$config['title_parent'])) }}</a>
                                    </li>
                                <?php } ?>
                                <li class="breadcrumb-item active">
                                    {{ ucfirst(trans('messages.'.$config['title'])) }}
                                </li>
                            <?php } ?>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @yield('body')                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop