@extends('layouts.app')

@section('title', 'Estadisticas')

@section('statics-css')
    @include('layouts/statics-css-1')
    <style>body{overflow-x: hidden;}</style>
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')
    <div class="row" style="min-height:550px;">
        <div class="col-md-6">
            <h3 class="margin-left-2 margin-top3">Estadisticas grupales para: {{$group_name}}</h3>
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
            <div id="group_statistics_theory"></div>
        </div>
        <div class="col-md-12">
            <div id="group_statistics_simulation"></div>
        </div>
        <div class="col-md-12">
            <div id="group_statistics_questionnaire"></div>
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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        $(document).ready(function(){
            var url = "{{ url('/admin/group/statistics/' . $group_name . '/theory') }}";
            $('#group_statistics_theory').load(encodeURI(url), function(){}).hide().fadeIn();
        });
        $('#filter').on('change', function() {

        });
    </script>
@endsection





