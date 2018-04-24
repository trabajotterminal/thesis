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
        .customBack {
            background: url({{URL::asset('images/cover.png')}});
            background-image: url({{URL::asset('images/cover.png')}});
            background-position-x: center;
            background-position-y: 0px;
            background-repeat: repeat-x;
        }
        body{
            min-height:100vh;
            overflow-x: hidden;
        }
    </style>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'index'])
@endsection
@section('content')
        <div class="row customBack" style="height: 90vh;">
            <div class="col-md-8 col-centered">
                <center>
                    <h1 class="dosis uppercase text-white textoTop" style="margin-top:50px;">Aprende un nuevo algoritmo</h1></center>
                    <div class="title-line-4 white align-center"></div>
                <center>
                <div class="domain-search-holder">
                    <form method="get" action="{{url('/search')}}" id="domain-search-holder">
                        <div class="input_holder">
                            <input class="input-text" name="input_search" id="dsearch" placeholder="Búsca algún algoritmo" type="search" required />
                            <input id="searchsubmit" value="Buscar" style="background-color: black" type="submit" />
                        </div>
                    </form>
                </div>
                <input class="input-text" style="visibility: hidden;" type="search"/>
                </center>
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
            </div>
        </div>
        <div class="row no-padding no-margin" style="margin:0px;padding:0px;">
            <div class="col-md-4 col-centered">
                <center><img src="{{ URL::asset('/images/best_user.png')}}" style="width:50%;height:50%;"/></center>
            </div>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-centered" style="margin-top:-10px;padding:0px;">
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


@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
@endsection





