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
    <div class="row" style="min-height: calc(100vh - 163px);">
        <div class="col-md-6">
            <h1 class="margin-left-2 margin-top3">{{$school_name}}</h1>
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
            <div id="school_statistics_theory"></div>
        </div>
        <div class="col-md-12">
            <div id="school_statistics_simulation"></div>
        </div>
        <div class="col-md-12">
            <div id="school_statistics_questionnaire"></div>
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script defer src="{{ asset('js/all.js')}}"></script>
    <script src="{{ asset('js/progress-circle/jquery.circlechart.js')}}"></script>
    <script src="{{ asset('js/universal/jquery.js')}}"></script>
    <script src="{{ asset('js/canvas.js')}}"></script>
    <script>
        $(document).ready(function(){
            var url = "{{ url('/admin/school/statistics/' . $school_name . '/theory') }}";
            $("#filter").val('0');
            $('#school_statistics_simulation').hide();
            $('#school_statistics_questionnaire').hide();
            $('#school_statistics_theory').load(encodeURI(url), function(){}).hide().fadeIn();
        });
        $('#filter').on('change', function() {
            var index = this.value;
            if(index == 0){
                var url = "{{ url('/admin/school/statistics/' . $school_name . '/theory') }}";
                $('#school_statistics_simulation').hide();
                $('#school_statistics_questionnaire').hide();
                $('#school_statistics_theory').load(encodeURI(url), function(){}).hide().fadeIn();
            }
            if(index == 1){
                var url = "{{ url('/admin/school/statistics/' . $school_name . '/questionnaire') }}";
                $('#school_statistics_simulation').hide();
                $('#school_statistics_theory').hide();
                $('#school_statistics_questionnaire').load(encodeURI(url), function(){}).hide().fadeIn();
            }
            if(index == 2){
                var url = "{{ url('/admin/school/statistics/' . $school_name . '/simulation') }}";
                $('#school_statistics_theory').hide();
                $('#school_statistics_questionnaire').hide();
                $('#school_statistics_simulation').load(encodeURI(url), function(){}).hide().fadeIn();
            }
        });
    </script>
@endsection





