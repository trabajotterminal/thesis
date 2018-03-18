@extends('layouts.app')

@section('title', 'Contenido')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <section class="section-less-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h2 class="section-title-2">{{$name}}</h2>
                    <p class="sub-title">Elige la forma de subir contenido teórico para el tema.</p>
                </div>
                <div class="col-md-6">
                    <div class="feature-box12 text-center bmargin" style="height:350px;">
                        <h3>Subir un archivo</h3>
                        <center><img src="{{ URL::asset('/images/folder-circle.png')}}" style="width:100px;height:100px;margin-bottom:30px;"/></center>
                        <form enctype="multipart/form-data" method="post" class="margin-top-1" action="{{ url('creator/topic/theory/register/file') }}">
                            {{ csrf_field() }}
                            <center><input class="btn btn-default" style="margin-bottom:10px;" name="input_file" type="file" id="input_file"></center>
                            <input type="hidden" name="topic_name" value="{{$name}}">
                            <div class="form-group">
                                <input class="form-control btn-primary margin-top" style="margin-top:33px;" type="submit" value="Subir archivo">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-box12 text-center bmargin" style="height:350px;">
                        <h3>Ingresar datos manualmente</h3>
                        <center><img src="{{ URL::asset('/images/keyboard.png')}}" style="width:150px;height:150px;margin-bottom:55px;"/></center>
                        <form method="post" action="{{ url('creator/topic/theory/register/manually') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="topic_name" value="{{$name}}">
                            <div class="form-group">
                                <input class="form-control btn-primary" type="submit" value="Comenzar"></a>
                            </div>
                        </form>
                        <br/>
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
@endsection





