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
        <div class="col-md-4">
            <div id="users_ranking" class="margin-top3 margin-left-2" style="overflow: hidden;width:95%;"></div>
        </div>
        <div class="col-md-4">
            <div id="groups_ranking" class="margin-top3 margin-left-2" style="overflow: hidden;width:95%;"></div>
        </div>
        <div class="col-md-4">
            <div id="schools_ranking" class="margin-top3" style="overflow: hidden;width:95%;"></div>
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





