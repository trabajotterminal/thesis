@php
    $routeQ             = "";
    $routeS             = "";
    $routeT             = "";
    $user = session('user');
@endphp
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
                        @php
                            for($j = 0; $j < count($references[$key]); $j++){
                                if($references[$key][$j] ->  type == 'C'){
                                    $routeQ = $references[$key][$j] -> approved_route;
                                }
                                if($references[$key][$j] ->  type == 'S'){
                                    $routeS = $references[$key][$j] -> approved_route;
                                }
                                if($references[$key][$j] ->  type == 'T'){
                                    $routeT = $references[$key][$j] -> approved_route;
                                }
                            }
                        @endphp
                        <div class="row" style="width:110%;margin-left:50px;margin-top:40px;">
                            @if(strlen($routeT))
                                <div class="col-md-3">
                                    <center><p><a href={{ url('/theory', $result -> topic_name) }}>Teoría</a></p></center>
                                </div>
                            @endif
                            @if(strlen($routeS))
                                <div class="col-md-3">
                                    <center><p><a href={{ url('/simulation', $result -> topic_name) }}>Simulación</a></p></center>
                                </div>
                            @endif
                            @if(strlen($routeQ))
                                @if($user)
                                    <div class="col-md-3">
                                        <center><p><a href={{ url('/questionnaire', $result -> topic_name) }}>Cuestionarios</a></p></center>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @php
                    $routeQ = "";
                    $routeT = "";
                    $routeS = "";
                @endphp
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





