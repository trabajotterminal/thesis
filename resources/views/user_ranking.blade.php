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
                        <option value="1">Cuestionarios</option>
                        <option value="2">Simulaciones</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div id="user_ranking_theory" ></div>
        </div>
        <div class="col-md-12">
            <div id="user_ranking_simulation"></div>
        </div>
        <div class="col-md-12">
            <div id="user_ranking_questionnaire"></div>
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    <script src="{{ asset('js/progress-circle/jquery.circlechart.js')}}"></script>
    <script src="{{ asset('js/universal/jquery.js')}}"></script>
    <script src="{{ asset('js/canvas.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("#filter").val('0');
            $('#user_ranking_questionnaire').hide();
            $('#user_ranking_simulation').hide();
            $('#user_ranking_theory').load('/creator/user/ranking/{{$user_id}}/theory',function(){}).hide().fadeIn();

        });
        $('#filter').on('change', function() {
            var index = this.value;
            if(index == 0){
                $('#user_ranking_simulation').hide();
                $('#user_ranking_questionnaire').hide();
                $('#user_ranking_theory').hide().fadeIn();
            }
            if(index == 1){
                $('#user_ranking_theory').hide();
                $('#user_ranking_simulation').hide();
                $('#user_ranking_questionnaire').load('/creator/user/ranking/{{$user_id}}/questionnaire',function(){}).hide().fadeIn();
            }
            if(index == 2){
                $('#user_ranking_theory').hide();
                $('#user_ranking_questionnaire').hide();
                $('#user_ranking_simulation').load('/creator/user/ranking/{{$user_id}}/simulation',function(){}).hide().fadeIn();
            }
        })
    </script>
@endsection





