@extends('layouts.app')

@section('title', 'Login')

@section('statics-css')
    @include('layouts/statics-css-1')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/js/smart-forms/smart-forms.css')}}" rel="stylesheet" >
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/js/masterslider/style/masterslider.css')}}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/js/owl-carousel/owl.carousel.css')}}" rel="stylesheet" >
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')
    <section class="sec-padding section-light" style="min-height:550px;background-color:white">
        <div class="container white">
            <div class="row">
                <div class="col-md-4 col-centered">
                    <div id="owl-demo3" class="owl-carousel">
                        <div class="item" id="firstItem">
                            <div class="smart-forms bmargin text-box white sec-moreless-padding" style="padding-left: 30px;padding-right: 30px;">
                                <center><h3 class="centered">Inicia Sesión</h3></center>
                                <br/>
                                <br/>
                                {!! Form::open(['url' => 'login/user']) !!}
                                <div>
                                    <div class="section">
                                        <label class="field prepend-icon">
                                            {!! Form::text('email', '', ['placeholder' => 'usuario  / email', 'class' => 'gui-input']) !!}
                                            {!! $errors->first('email', '<p class="help-block" style="color:red;">:message</p>') !!}
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span> </label>
                                    </div>
                                    <div class="section">
                                        <label class="field prepend-icon">
                                            {!! Form::password('password', ['class' => 'gui-input', 'placeholder' => 'Contraseña']) !!}
                                            {!! $errors->first('password', '<p class="help-block" style="color:red;">:message</p>') !!}
                                            <span class="field-icon"><i class="fa fa-unlock-alt"></i></span> </label>
                                    </div>
                                </div>
                                <br>
                                <div class="form-footer">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <center>{!! Form::submit('Entrar', ['class' => 'button btn-primary orange-2']) !!}</center>
                                    <br>
                                    @if(session()->has('wrong_credentials'))
                                        <div class="alert alert-danger }}">
                                            {!! session('wrong_credentials') !!}
                                        </div>
                                    @endif
                                </div>
                                {!! Form::close() !!}

                            </div>
                        </div>
                        <div class="item" id="secondItem" style="overflow-y: hidden;">
                            <div class="smart-forms bmargin text-box white sec-moreless-padding" style="padding-left: 30px;padding-right: 30px;">
                                <center><h3 class="centered">Registrate</h3></center>
                                <br/>
                                <br/>
                                {!! Form::open(['url' => 'register/user']) !!}
                                <div>
                                    <div class="section">
                                        <label class="field prepend-icon">
                                            {!! Form::text('username', '', ['placeholder' => 'Nombre de usuario', 'class' => 'gui-input']) !!}
                                            <span class="field-icon"><i class="fa fa-user"></i></span> </label>
                                            {!! $errors->first('username', '<p class="help-block" style="color:red;">:message</p>') !!}
                                    </div>
                                    <div class="section">
                                        <label class="field prepend-icon">
                                            {!! Form::text('register_email', '', ['placeholder' => 'ejemplo@gmail.com', 'class' => 'gui-input']) !!}
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span> </label>
                                            {!! $errors->first('register_email', '<p class="help-block" style="color:red;">:message</p>') !!}
                                    </div>
                                    <div class="section">
                                        <label class="field prepend-icon">
                                            {!! Form::password('register_password', ['class' => 'gui-input', 'placeholder' => 'Contraseña']) !!}
                                            <span class="field-icon"><i class="fa fa-unlock-alt"></i></span> </label>
                                            {!! $errors->first('register_password', '<p class="help-block" style="color:red;">:message</p>') !!}
                                    </div>
                                </div>
                                <br>
                                <div class="form-footer">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <center>{!! Form::submit('Aceptar', ['class' => 'button btn-primary orange-2']) !!}</center>
                                    <br>
                                    @if(session()->has('user_email_exists'))
                                        <div class="alert alert-danger }}">
                                            {!! session('user_email_exists') !!}
                                        </div>
                                    @endif
                                    @if(session()->has('user_name_exists'))
                                        <div class="alert alert-danger }}">
                                            {!! session('user_name_exists') !!}
                                        </div>
                                    @endif
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection
@section('statics-js')
    @include('layouts/statics-js-1')
    <script src="{{URL::asset('/js/owl-carousel/owl.carousel.js')}}"></script>
    <script src="{{URL::asset('/js/owl-carousel/custom.js')}}"></script>
    <script>
        $(document).ready(function() {
            var carousel = $("#owl-demo3");
            var indexPage = <?php echo session('indexPage') ?>;
            carousel.trigger('owl.jumpTo', indexPage)
        });
    </script>
@endsection





