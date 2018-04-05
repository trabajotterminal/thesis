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
    <div class="fluid-container" style="min-height:550px">

        <div class="row" style="margin-top: 10px;">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="ranking_filter" class="col-sm-2 control-label" style="margin-left:40px;"> Filtro </label>
                    <div class="col-sm-8">
                        <select class="form-control" name="ranking_filter" id="ranking_filter">
                            <option value="1">Mejores usuarios</option>
                            <option value="2">Mejores clubes</option>
                            <option value="3">Mejores instituciones</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row" id="users_ranking_container">
            <div class="col-md-11 col-centered">
                <div id="users_ranking"></div>
            </div>
        </div>

        <div class="row" id="groups_ranking_container">
            <div class="col-md-11 col-centered">
                <div id="groups_ranking"></div>
            </div>
        </div>

        <div class="row" id="schools_ranking_container">
            <div class="col-md-11 col-centered">
                <div id="schools_ranking"></div>
            </div>
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
            $("#ranking_filter").val($("#ranking_filter option:first").val());
            $('#users_ranking_container').hide();
            $('#groups_ranking_container').hide();
            $('#schools_ranking_container').hide();
            $('#users_ranking').load('/admin/users/ranking',function(){}).hide().fadeIn();
            $('#groups_ranking').load('/admin/groups/ranking',function(){}).hide().fadeIn();
            $('#schools_ranking').load('/admin/schools/ranking',function(){}).hide().fadeIn();
            $('#users_ranking_container').fadeIn(1000);
        });

        $('#ranking_filter').change(function(){
            var selected_value = $(this).val();
            $('#users_ranking_container').hide();
            $('#groups_ranking_container').hide();
            $('#schools_ranking_container').hide();
            if(selected_value == '1'){
                $('#users_ranking_container').fadeIn(1000);
            }
            if(selected_value == '2'){
                $('#groups_ranking_container').fadeIn(1000);
            }
            if(selected_value == '3'){
                $('#schools_ranking_container').fadeIn(1000);
            }
        });
    </script>
@endsection





