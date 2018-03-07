@extends('layouts.app')

@section('title', 'Contenido')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <section class="sec-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h2 class="section-title-2 uppercase">{{$name}}</h2>
                    <div class="title-line-8"></div>
                    <p class="sub-title">Elige la forma de subir contenido teórico para el tema.</p>
                </div>
                <div class="col-md-6">
                    <div class="feature-box12 text-center bmargin" style="height:250px;">
                        <h3 >Subir un archivo</h3>
                        <i class="fa fa-file" style="font-size:30px;margin-bottom:20px;"></i>
                        <form enctype="multipart/form-data" method="post" class="margin-top-1" action="{{ url('admin/topic/theory/register/file') }}">
                            {{ csrf_field() }}
                            <center><input name="input_file" type="file" id="input_file"></center>
                            <input type="hidden" name="topic_name" value="{{$name}}">
                            <br>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Subir archivo">
                            </div>
                        </form>
                        <br/>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-box12 text-center bmargin" style="height:250px;">
                        <h3>Ingresar datos manualmente</h3>
                        <i class="fa fa-keyboard-o" style="font-size:50px;"></i>
                        <br>
                        <form method="post" action="{{ url('admin/topic/theory/register/manually') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="topic_name" value="{{$name}}">
                            <input class="btn btn-primary" style="margin-top:45px;" type="submit" value="Comenzar"></a>
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





