@extends('layouts.app')

@section('title', 'Login')

@section('statics-css')
    @include('layouts/statics-css-1')
    <link rel="stylesheet" type="text/css" href="/js/smart-forms/smart-forms.css">
    <link rel="stylesheet" href="/js/masterslider/style/masterslider.css" />
    <link href="/js/owl-carousel/owl.carousel.css" rel="stylesheet">
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')
    <section class="sec-padding section-light" style="height:550px;background-color:white">
        <div class="container white">
            <div class="row">
                <div class="col-md-4 col-centered border slide-controls-color-10" >
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
                                                {!! Form::text('email', '', ['placeholder' => 'ejemplo@gmail.com', 'class' => 'gui-input']) !!}
                                                <span class="field-icon"><i class="fa fa-envelope"></i></span> </label>
                                        </div>
                                        <div class="section">
                                            <label class="field prepend-icon">
                                                {!! Form::password('password', ['class' => 'gui-input', 'placeholder' => 'Contraseña']) !!}
                                                <span class="field-icon"><i class="fa fa-unlock-alt"></i></span> </label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-footer">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <center>{!! Form::submit('Entrar', ['class' => 'button btn-primary orange-2']) !!}</center>
                                        <br>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @if(session()->has('wrong_credentials'))
                                            <div class="alert alert-danger }}">
                                                {!! session('wrong_credentials') !!}
                                            </div>
                                        @endif
                                    </div>
                                {!! Form::close() !!}

                            </div>
                        </div>
                        <div class="item" id="secondItem">
                            <div class="smart-forms bmargin text-box white sec-moreless-padding" style="padding-left: 30px;padding-right: 30px;">
                                <center><h3 class="centered">Registrate</h3></center>
                                <br/>
                                <br/>
                                {!! Form::open(['url' => 'register/user']) !!}
                                    <div>
                                        <div class="section">
                                            <label class="field prepend-icon">
                                                {!! Form::text('email', '', ['placeholder' => 'ejemplo@gmail.com', 'class' => 'gui-input']) !!}
                                                <span class="field-icon"><i class="fa fa-envelope"></i></span> </label>
                                        </div>
                                        <div class="section">
                                            <label class="field prepend-icon">
                                                {!! Form::password('password', ['class' => 'gui-input', 'placeholder' => 'Contraseña']) !!}
                                                <span class="field-icon"><i class="fa fa-unlock-alt"></i></span> </label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-footer">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <center>{!! Form::submit('Aceptar', ['class' => 'button btn-primary orange-2']) !!}</center>
                                        <br>
                                        @if ($errors -> any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @if(session()->has('user_exists'))
                                            <div class="alert alert-danger }}">
                                                {!! session('user_exists') !!}
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
    <script src="/js/owl-carousel/owl.carousel.js"></script>
    <script src="/js/owl-carousel/custom.js"></script>
@endsection





