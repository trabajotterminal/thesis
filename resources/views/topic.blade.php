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
    <h1 style="margin-left:30px;margin-top:30px;">{{$topic_name}}</h1>
    <section class="parallax-section11">
        <div class="container sec-tpadding-3 sec-bpadding-2">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box12">
                        <div class="iconbox-small round gray center"><span class="icon-paperclip"></span></div>
                        <h3 class="margin-top3">Teoria</h3>
                        <br/>
                        @if($references['T'])
                            <p>Éste tema cuenta ya con un archivo de teoria.</p>
                            <a class="read-more black" href="#"> Archivo </a>
                        @else
                            <p>Sin archivo de teoria.</p>
                            <a class="read-more black" href="{{url('admin/topic/'.$topic_name.'/theory')}}">Agregar Teoría</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box12">
                        <div class="iconbox-small round gray center"><span class="icon-beaker"></span></div>
                        <h3 class="margin-top3">Simulación</h3>
                        <br/>
                        @if($references['S'])
                            <p>Éste tema ya cuenta con archivos de simulación.</p>
                            <a class="read-more black" href="#">  </a>
                        @else
                            <p>Sin archivos de simulación.</p>
                            <a class="read-more black" href="{{url('admin/topic/'.$topic_name.'/simulation')}}">Agregar Simulación</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box12">
                        <div class="iconbox-small round gray center"><span class=" icon-edit"></span></div>
                        <h3 class="margin-top3">Cuestionarios</h3>
                        <br/>
                        @if($references['C'])
                            <p>Éste tema cuenta ya con cuestionarios.</p>
                            <a class="read-more black" href="#"> Archivo</a>
                        @else
                            <p>Sin archivos de cuestionario.</p>
                            <a class="read-more black" href="{{url('admin/topic/'.$topic_name.'/questionnaire')}}">Agregar Cuestionarios</a>
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





