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
                    <p class="sub-title">Elige la forma de subir un cuestionario para el tema.</p>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                    <div class="feature-box12 text-center bmargin" style="height:370px;">
                        <h3 >Subir un archivo</h3>
                        <center><img src="{{ URL::asset('/images/folder-circle.png')}}" style="width:100px;height:100px;margin-bottom:30px;margin-top:30px;"/></center>
                        <form enctype="multipart/form-data" method="post" action="{{ url('creator/topic/questionnaire/register/file') }}">
                            {{ csrf_field() }}
                            <center><input name="input_file" type="file" id="input_file" accept="text/xml"></center>
                            <input type="hidden" name="topic_name" value="{{$name}}">
                            @php
                                $errors = Session::get('file_errors_questionnaire');
                                if($errors){
                                    for($i = 0; $i < count($errors); $i++){
                                        echo "<p style='margin-left:20px;color:red'>".$errors[$i]."</p>";
                                   }
                                }
                            @endphp
                            <div class="form-group">
                                <input class="form-control btn-success" type="submit" style="margin-top:25px;" value="Subir archivo">
                            </div>
                        </form>
                        <p></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-box12 text-center bmargin" style="height:370px;">
                        <h3>Ingresar datos manualmente</h3>
                        <center><img src="{{ URL::asset('/images/keyboard.png')}}" style="width:150px;height:150px;margin-bottom:35px;"/></center>
                        <br>
                        <form method="post" action="{{ url('creator/topic/questionnaire/register/manually') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="topic_name" value="{{$name}}">
                            <div class="form-group">
                                <input class="form-control btn-success" type="submit" value="Comenzar">
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





