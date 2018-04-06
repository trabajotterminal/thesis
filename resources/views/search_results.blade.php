@extends('layouts.app')
@section('title', 'Búsqueda')
@section('statics-css')
    @include('layouts/statics-css-1')
    <link rel="stylesheet" href="{{ URL::asset('/css/cards.css')}}" type="text/css" />
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'index'])
@endsection
@section('content')
    <div class="container" style="min-height:550px;">
        <div class="row">
            <div class="col-md-6">
                <br>
                <h4>Resultados para: <u>{{$input}}</u></h4>
                @if(count($search_results) == 0 && $should_display_predicted_word)
                    <h5>Quizás quisiste decir: <a href="/search?input_search={{$predicted_word}}" style="color:blue">{{$predicted_word}}</a></h5>
                @endif
                <hr>
            </div>
        </div>
        <div class="row" style="margin-top:30px;">
            @foreach($search_results as $key => $result)
                @php
                    $randomClassArray   = ['blue', 'purple', 'green', 'yellow', 'red'];
                    $randomClass        = rand(0, count($randomClassArray) - 1);
                    $randomClass        = $randomClassArray[$randomClass];
                @endphp
                <div class="col-md-4" style="margin-bottom:10px;">
                    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
                    <div class="card {{$randomClass}}">
                        <div class="overlay"></div>
                        <div class="circle">
                        </div>
                        <p style="font-size:30px;">{{$result -> topic_name}}</p>
                        <div class="row" style="width:110%;margin-left:50px;margin-top:40px;">
                            <div class="col-md-3">
                                <p><a href="#">Teoria</a></p>
                            </div>
                            <div class="col-md-3">
                                <p><a href="#">Simulación</a></p>
                            </div>
                            <div class="col-md-3">
                                <p><a href="#">Cuestionarios</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if(count($search_results) == 0)
                <div class="col-md-12">
                    <h1 class="text-center" style="padding-top: 70px;">No se encontraron resultados.</h1>
                </div>
            @endif
        </div>

    </div>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script>

    </script>
@endsection





