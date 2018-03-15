@extends('layouts.app')

@section('title', 'Estadisticas')

@section('statics-css')
    @include('layouts/statics-css-1')
    <link rel="stylesheet" href="{{ asset('/css/dinatable.css')}}" type="text/css" />
    <style>
        body{overflow-x: hidden;}
    </style>
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')
    <div class="row" style="min-height:550px;">
        <div class="row">
            <div class="col-md-11 col-centered">
                <center>
                    <h3 class="margin-top3">Mejores usuarios</h3>
                    <br>
                    <img src="{{ URL::asset('/images/cup.png')}}" style="width:100px;height:100px;"/>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11 col-centered">
                <div id="users_ranking"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row">
            <div class="col-md-11 col-centered">
                <center>
                    <h3 class="margin-top3">Mejores clubes</h3>
                    <br>
                    <img src="{{ URL::asset('/images/group.png')}}" style="width:100px;height:100px;"/>
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11 col-centered">
                <div id="groups_ranking"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-11 col-centered">
            <center>
                <h3 class="margin-top3">Mejores escuelas</h3>
                <br>
                <img src="{{ URL::asset('/images/school.png')}}" style="width:100px;height:100px;"/>
            </center>
        </div>
    </div>
    <div class="row">
        <div class="col-md-11 col-centered">
            <div id="schools_ranking"></div>
        </div>
    </div>



@endsection
@section('footer')
    @include('layouts/footer')
@endsection
@section('statics-js')
    @include('layouts/statics-js-1')
    <script src="{{ asset('/js/dinatable.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#users_ranking').load('/admin/users/ranking',function(){}).hide().fadeIn();
            $('#groups_ranking').load('/admin/groups/ranking',function(){}).hide().fadeIn();
            $('#schools_ranking').load('/admin/schools/ranking',function(){}).hide().fadeIn();
        });

    </script>
@endsection





