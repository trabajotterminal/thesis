@extends('layouts.app')

@section('title', 'Inicio')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')
    <div class="row" style="min-height:550px;">
        <div class="col-md-4">
            <div id="users_ranking" ></div>
        </div>
        <div class="col-md-4">
            <div id="groups_ranking"></div>
        </div>
        <div class="col-md-4">
            <div id="schools_ranking"></div>
        </div>
    </div>



@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script>
        $(document).ready(function(){
            $('#users_ranking').load('/admin/users/ranking',function(){}).hide().fadeIn();
            $('#groups_ranking').load('/admin/groups/ranking',function(){}).hide().fadeIn();
            $('#schools_ranking').load('/admin/schools/ranking',function(){}).hide().fadeIn();
        });

    </script>
@endsection





