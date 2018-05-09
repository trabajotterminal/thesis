@extends('layouts.app')
@section('title', 'Error 404')
@section('statics-css')
    @include('layouts/statics-css-1')
    <style>
        body { background-color: white;}
        .error-template {padding: 0px 15px;text-align: center;}
        .error-actions {margin-top:0px;margin-bottom:15px;}
        .error-actions .btn { margin-right:10px; }
    </style>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'index'])
@endsection
@section('content')
    <div class="container" style="min-height: calc(100vh - 163px);">
        <div class="row" style="margin-top:50px;">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>¡Uups!</h1>
                    <h2>Error 404</h2>
                    <div class="error-details">
                        <span>Lo sentimos, la página que has solicitado, no la hemos podido encontrar.</span>
                    </div>
                    <img src="{{ URL::asset('/images/robot.png')}}" style="width:200px;height:200px;margin-top:30px;"/>
                    <br><br>
                    <div class="error-actions">
                        <a href="/" class="btn btn-danger btn-md">
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
@endsection





