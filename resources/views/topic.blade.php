@extends('layouts.app')

@section('title', 'Tema')

@section('statics-css')
    @include('layouts/statics-css-1')
    <link rel="stylesheet" href="{{asset('css/et-line-font/et-line-font.css')}}">
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <section class="parallax-section11">
        <h1 style="margin-left:30px;margin-top:30px;">{{$topic_name}}</h1>
        <div class="container " style="min-height:380px;">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box12">
                        <center><img src="{{ URL::asset('/images/book_hand.png')}}" style="width:100px;height:100px;"/></center>
                        <h3 class="margin-top3">Teoria</h3>
                        <br/>
                        @if($references['T'])
                            @if(!$is_approval_pending['T'])
                                <form enctype="multipart/form-data" method="post" class="margin-top-1" action="{{ url('creator/topic/theory/edit/file') }}">
                                    {{ csrf_field() }}
                                    <center><input class="btn btn-default" style="margin-bottom:10px;width:100%;" name="input_file" type="file" id="input_file" accept="text/xml"></center>
                                    <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                    @php
                                        $errors = Session::get('file_errors_theory');
                                        if($errors){
                                            for($i = 0; $i < count($errors); $i++){
                                                echo "<p style='margin-left:20px;color:red'>".$errors[$i]."</p>";
                                           }
                                        }
                                    @endphp
                                    @php
                                        $success = Session::get('file_success_theory');
                                        if($success){
                                            for($i = 0; $i < count($success); $i++){
                                                echo "<p style='margin-left:20px;color:green'>".$success[$i]."</p>";
                                           }
                                        }
                                    @endphp
                                    <div class="form-group">
                                        <input class="form-control btn-primary margin-top" style="margin-top:33px;" type="submit" value="Remplazar con un nuevo archivo">
                                    </div>
                                </form>
                                @if($needs_approval['T'])
                                    <form  method="post" class="margin-top-1" action="{{ url('creator/topic/theory/submitReview') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                        <div class="form-group">
                                            <input class="form-control btn-warning" style="margin-top:13px;" type="submit" value="Enviar revisión">
                                        </div>
                                    </form>
                                @endif
                                <form  method="post" class="margin-top-1" action="{{ url('creator/topic/theory/edit/manually') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                    <a href="#" style="position:absolute;left:5px;bottom:2px;text-decoration:underline;" onclick="$(this).closest('form').submit()">Editar por interfaz</a>
                                </form>
                            @else
                                <p>La teoría está esperando la aprobación del administrador.</p>
                            @endif
                            <form  method="post" class="margin-top-1" action="{{ url('creator/topic/theory/download') }}">
                                {{ csrf_field() }}
                                <a href="#" style="position:absolute;right:5px;bottom:2px;text-decoration:underline;" onclick="$(this).closest('form').submit()">Descargar teoría</a>
                                <input type="hidden" name="topic_name" value="{{$topic_name}}">
                            </form>
                        @else
                            <p>Sin archivo de teoria.</p>
                            <a class="read-more black" href="{{url('creator/topic/'.$topic_name.'/theory')}}">Agregar Teoría</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box12">
                        <center><img src="{{ URL::asset('/images/chemistry.png')}}" style="width:100px;height:100px;"/></center>
                        <h3 class="margin-top3">Simulación</h3>
                        <br/>
                        @if($references['S'])
                            @if(!$is_approval_pending['S'])
                                @if($creation_type['S'] == 'file')
                                    <form enctype="multipart/form-data" method="post" class="margin-top-1" action="{{ url('creator/topic/simulation/edit/file') }}">
                                        {{ csrf_field() }}
                                        <center><input class="btn btn-default" style="margin-bottom:10px;width:100%;" name="input_file" type="file" id="input_file" accept="application/zip"></center>
                                        <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                        @php
                                            $errors = Session::get('file_errors_simulation');
                                            if($errors){
                                                for($i = 0; $i < count($errors); $i++){
                                                    echo "<p style='margin-left:20px;color:red'>".$errors[$i]."</p>";
                                               }
                                            }
                                        @endphp
                                        @php
                                            $success = Session::get('file_success_simulation');
                                            if($success){
                                                for($i = 0; $i < count($success); $i++){
                                                    echo "<p style='margin-left:20px;color:green'>".$success[$i]."</p>";
                                               }
                                            }
                                        @endphp
                                        <div class="form-group">
                                            <input class="form-control btn-primary margin-top" style="margin-top:33px;" type="submit" value="Remplazar con un nuevo archivo">
                                        </div>
                                    </form>
                                @endif
                                @if($needs_approval['S'])
                                        <form  method="post" class="margin-top-1" action="{{ url('creator/topic/simulation/submitReview') }}">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                            <div class="form-group">
                                                <input class="form-control btn-warning" style="margin-top:13px;" type="submit" value="Enviar revisión">
                                            </div>
                                        </form>
                                @endif
                            @else
                                <p>La simulación está esperando la aprobación del administrador.</p>
                            @endif
                        @else
                            <p>Sin archivos de simulación.</p>
                            <a class="read-more black" href="{{url('creator/topic/'.$topic_name.'/simulation')}}">Agregar Simulación</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box12">
                        <center><img src="{{ URL::asset('/images/test.png')}}" style="width:100px;height:100px;"/></center>
                        <h3 class="margin-top3">Cuestionarios</h3>
                        <br/>
                        @if($references['C'])
                            @if(!$is_approval_pending['C'])
                                <form enctype="multipart/form-data" method="post" class="margin-top-1" action="{{ url('creator/topic/questionnaire/edit/file') }}">
                                    {{ csrf_field() }}
                                    <center><input class="btn btn-default" style="margin-bottom:10px;width:100%;" name="input_file" type="file" id="input_file"></center>
                                    <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                    @php
                                        $errors = Session::get('file_errors_questionnaire');
                                        if($errors){
                                            for($i = 0; $i < count($errors); $i++){
                                                echo "<p style='margin-left:20px;color:red'>".$errors[$i]."</p>";
                                           }
                                        }
                                    @endphp
                                    @php
                                        $success = Session::get('file_success_questionnaire');
                                        if($success){
                                            for($i = 0; $i < count($success); $i++){
                                                echo "<p style='margin-left:20px;color:green'>".$success[$i]."</p>";
                                           }
                                        }
                                    @endphp
                                    <div class="form-group">
                                        <input class="form-control btn-primary margin-top" style="margin-top:33px;" type="submit" value="Remplazar con un nuevo archivo">
                                    </div>
                                </form>
                                @if($needs_approval['C'])
                                    <form  method="post" class="margin-top-1" action="{{ url('creator/topic/questionnaire/submitReview') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                        <div class="form-group">
                                            <input class="form-control btn-warning" style="margin-top:13px;" type="submit" value="Enviar revisión">
                                        </div>
                                    </form>
                                @endif
                                <form  method="post" class="margin-top-1" action="{{ url('creator/topic/questionnaire/edit/manually') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                    <a href="#" style="position:absolute;left:5px;bottom:2px;text-decoration:underline;" onclick="$(this).closest('form').submit()">Editar por interfaz</a>
                                </form>
                            @else
                                <p>Los cuestionarios están esperando la aprobación del administrador.</p>
                            @endif
                            <form  method="post" class="margin-top-1" action="{{ url('creator/topic/questionnaire/download') }}">
                                {{ csrf_field() }}
                                <a href="#" style="position:absolute;right:5px;bottom:2px;text-decoration:underline;" onclick="$(this).closest('form').submit()">Descargar cuestionario</a>
                                <input type="hidden" name="topic_name" value="{{$topic_name}}">
                            </form>
                        @else
                            <p>Sin archivos de cuestionario.</p>
                            <a class="read-more black" href="{{url('creator/topic/'.$topic_name.'/questionnaire')}}">Agregar Cuestionarios</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br><br><br>
    </section>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
@endsection





