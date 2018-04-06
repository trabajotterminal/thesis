@php
    $i = 0;
    $routeQ  = "";
    $routeT  = "";
    $routeS  = "";
    $user = session('user');
@endphp
@extends('layouts.app')
@section('title', 'Categoria')
@section('statics-css')
    @include('layouts/statics-css-1')
    <link rel="stylesheet" href="{{ URL::asset('/css/cards.css')}}" type="text/css" />
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <div class="section-overlay bg-opacity-0">
        <div class="container  sec-bpadding-2">
            <br>
            <h3 class="raleway text-black uppercase">{{$category_name}}</h1>
            <br>
            <div class="row-md-5" id="category" style="min-height:390px;">
                    @while($i < count($topics))
                        @php
                            $count_reference = 0;
                            for($j = 0; $j < count($references[$i]); $j++){
                                if($references[$i][$j] ->  type == 'C'){
                                    $routeQ = $references[$i][$j] ->  approved_route;
                                    $count_reference ++;
                                }
                                if($references[$i][$j] ->  type == 'S'){
                                    $routeS = $references[$i][$j] ->  approved_route;
                                    $count_reference ++;
                                }
                                if($references[$i][$j] ->  type == 'T'){
                                    $routeT = $references[$i][$j] ->  approved_route;
                                    $count_reference ++;
                                }
                            }
                            $randomClassArray   = ['blue', 'purple', 'green', 'yellow', 'red'];
                            $randomClass        = rand(0, count($randomClassArray) - 1);
                            $randomClass        = $randomClassArray[$randomClass];
                        @endphp
                        @if($i < count($topics))
                            <div class="col-md-4" style="margin-bottom:10px;">
                                <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
                                <div class="card {{$randomClass}}">
                                    <div class="overlay"></div>
                                    <div class="circle">
                                    </div>
                                    <p style="font-size:30px;">{{$topics[$i] -> approved_name}}</p>
                                    <div class="row" style="width:100%;margin-left:50px;margin-top:40px;">
                                        @if($count_reference == 3)
                                            @if(strlen($routeT))
                                                <div class="col-md-3">
                                                    <center><p><a href={{ url('/theory', $topics[$i] -> approved_name) }}>Teoría</a></p></center>
                                                </div>
                                            @endif
                                            @if(strlen($routeS))
                                                <div class="col-md-3">
                                                    <center><p><a href={{ url('/simulation', $topics[$i] -> approved_name) }}>Simulación</a></p></center>
                                                </div>
                                            @endif
                                            @if(strlen($routeQ))
                                                @if($user)
                                                    <div class="col-md-3">
                                                        <center><p><a href={{ url('/questionnaire', $topics[$i] -> approved_name) }}>Cuestionarios</a></p></center>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        @if($count_reference == 2)
                                            @if(strlen($routeT))
                                                <div class="col-md-3">
                                                    <center><p><a href={{ url('/theory', $topics[$i] -> approved_name) }}>Teoría</a></p></center>
                                                </div>
                                            @endif
                                            @if(strlen($routeS))
                                                <div class="col-md-3">
                                                    <center><p><a href={{ url('/simulation', $topics[$i] -> approved_name) }}>Simulación</a></p></center>
                                                </div>
                                            @endif
                                            @if(strlen($routeQ))
                                                @if($user)
                                                    <div class="col-md-3">
                                                        <center><p><a href={{ url('/questionnaire', $topics[$i] -> approved_name) }}>Cuestionarios</a></p></center>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        @if($count_reference == 1)
                                            @if(strlen($routeT))
                                                <div class="col-md-3">
                                                    <center><p><a href={{ url('/theory', $topics[$i] -> approved_name) }}>Teoría</a></p></center>
                                                </div>
                                            @endif
                                            @if(strlen($routeS))
                                                <div class="col-md-3">
                                                    <center><p><a href={{ url('/simulation', $topics[$i] -> approved_name) }}>Simulación</a></p></center>
                                                </div>
                                            @endif
                                            @if(strlen($routeQ))
                                                @if($user)
                                                    <div class="col-md-3">
                                                        <center><p><a href={{ url('/questionnaire', $topics[$i] -> approved_name) }}>Cuestionarios</a></p></center>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @php
                                $i++
                            @endphp
                        @endif
                        @php
                            $routeQ = "";
                            $routeT = "";
                            $routeS = "";
                        @endphp
                    @endwhile
                    @if(count($topics) == 0)
                        <h1 class="text-center" style="padding-top: 150px;">Aún no hay temas para esta categoria.</h1>
                    @endif
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-2')
@endsection





