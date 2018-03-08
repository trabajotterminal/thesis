@extends('layouts.app')

@section('title', 'Estadisticas')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')
    <div class="row" style="min-height:550px;">
        <div class="col-md-6">
            <h1 class="margin-left-2 margin-top3">Estadisticas para: {{$user_id}}</h1>
        </div>
        <div class="col-md-6">
            <div class="form-group margin-top4">
                <label class="col-sm-2 control-label"> Filtro: </label>
                <div class="col-sm-10">
                    <select class="form-control" id="filter" style="width:250px;">
                        <option value="0" selected>Teoría</option>
                        <option value="1">Cuestionario</option>
                        <option value="2">Simulación</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div id="user_ranking_theory" ></div>
        </div>
        <div class="col-md-12">
            <div id="user_ranking_simulation" ></div>
        </div>
        <div class="col-md-12">
            <div id="user_ranking_questionnaire" ></div>
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
            $("#filter").val('0');
            $('#user_ranking_theory').load('/admin/user/ranking/{{$user_id}}',function(){}).hide().fadeIn();
        });
    </script>
@endsection





