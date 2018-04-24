@php
    $users_table        = [];
    $profile_picture    = [];
    $school_name        = [];
    $group_name         = [];
    $index              = 0;
    $user_id            = session('user_id');
    for($i = 0; $i < count($ranked_users) && $index < 10; $i++){
        $users_table[$index]        = $ranked_users[$i] -> username;
        $profile_picture[$index]    = $ranked_users[$i] -> profile_picture;
        $group_name[$index]         = $ranked_users[$i] -> group_name;
        $school_name[$index]        = $ranked_users[$i] -> school_name;
        $index++;
    }
    for($i = 0; $i < count($non_ranked_users) && $index < 10; $i++){
        $users_table[$index]        = $non_ranked_users[$i] -> username;
        $profile_picture[$index]    = $non_ranked_users[$i] -> profile_picture;
        $group_name[$index]         = $non_ranked_users[$i] -> group_name;
        $school_name[$index]        = $non_ranked_users[$i] -> school_name;
        $index++;
    }
@endphp
@extends('layouts.app')
@section('title', 'Inicio')
@section('statics-css')
    @include('layouts/statics-css-1')
    <style>
        .parallax-section26 {
            background: url({{URL::asset('images/cover.png')}});
            background-image: url({{URL::asset('images/cover.png')}});
            background-position-x: center;
            background-position-y: 0px;
            background-repeat: repeat-x;
        }
        body{
            min-height:100vh;
        }
    </style>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'index'])
@endsection
@section('content')
    <section class="parallax-section26">
        <div class="section-overlay bg-opacity-0">
            <div class="container">
                <div class="row" style="margin-bottom: 250px;">
                    <div class="col-md-8 col-centered">
                        <br><br>
                        <h1 class="dosis uppercase less-mar3 text-white textoTop margin-top5">Aprende un nuevo algoritmo</h1>
                        <div class="title-line-4 white align-center"></div>
                        <div class="clearfix"></div>
                        <!--end title-->
                        <br/>
                        <div class="domain-search-holder">
                            <form method="get" action="{{url('/search')}}" id="domain-search-holder">
                                <div>
                                    <input class="input-text" name="input_search" id="dsearch" placeholder="Búsca algún algoritmo" type="text" required />
                                    <input id="searchsubmit" value="Buscar" style="background-color: black" type="submit" />
                                </div>
                            </form>
                        </div>
                        <center>
                            <div class="row">
                                <div class="col-md-3">
                                    <span class="left-move"><a class="text-white uppercase" href="{{URL('search?input_search=Segment')}}">Segment</a></span>
                                </div>
                                <div class="col-md-3">
                                    <span class="left-move"><a class="text-white uppercase" href="{{URL('search?input_search=Fenwick')}}">Fenwick</a></span>
                                </div>
                                <div class="col-md-3">
                                    <span class="right-move"><a class="text-white uppercase" href="{{URL('search?input_search=Búsqueda')}}">Búsqueda</a></span>
                                </div>
                                <div class="col-md-3">
                                    <span class="right-move"><a class="text-white uppercase" href="{{URL('search?input_search=Kruskal')}}">Kruskal</a></span>
                                </div>
                            </div>
                        </center>
                        <!--<span class="text-white">Plataforma para el aprendizaje de algoritmos</span> </div>-->
                    </div>
                </div>
                <div class="row no-padding no-margin" style="margin:0px;padding:0px;">
                    <div class="col-md-4 col-centered">
                        <img src="{{ URL::asset('/images/best_user.png')}}" style="margin-top:-50px;width:50%;height:50%;"/>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:-10px;padding:0px;">
                        <div class="panel price panel-red no-margin no-padding">
                            <div class="panel-body text-center">
                                <p  style="font-size:30px">
                                    <strong>Los mejores usuarios</strong>
                                </p>
                            </div>
                            <ul class="list-group list-group-flush text-center">
                                @foreach($users_table as $key => $user)
                                    <div class="row" style="margin-top:5px;">
                                        <div class="col-md-3">
                                            @if(($key + 1) >= 1 && ($key +1) <= 3)
                                                <img src="{{ URL::asset('/images/gold_medal.png')}}" style="width:35px;height:35px;"/>
                                            @endif
                                            @if(($key + 1) >= 4 && ($key +1) <= 7)
                                                <img src="{{ URL::asset('/images/silver_medal.png')}}" style="width:35px;height:35px;"/>
                                            @endif
                                            @if(($key + 1) >= 8 && ($key +1) <= 10)
                                                <img src="{{ URL::asset('/images/bronze_medal.png')}}" style="width:35px;height:35px;"/>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <img src="{{$profile_picture[$key]}}" style="width:35px;height:35px;float:left;margin-right:5px;"/>
                                            <h5 style="float:left;">{{$users_table[$key]}}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 style="float:left;font-size:13px;">{{$school_name[$key]}}</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 style="font-size:13px;">{{$group_name[$key]}}</h5>
                                        </div>
                                    </div>
                                @endforeach
                            </ul>
                            <div class="panel-footer">
                                @if(!($user_id))
                                    <a class="btn btn-lg btn-danger" href="{{URL('/login')}}">Registrate ahora</a>
                                @endif
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
@endsection





