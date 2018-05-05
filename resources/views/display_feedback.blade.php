@extends('layouts.app')
@section('title', 'Cuestionario')
@section('statics-css')
    @include('layouts/statics-css-2')
    <link rel="stylesheet" type="text/css" href="/js/smart-forms/smart-forms.css">
    <link rel="stylesheet" href="/js/masterslider/style/masterslider.css" />
    <link href="/js/owl-carousel/owl.carousel.css" rel="stylesheet">
    <script src="{{ asset('/js/cytoscape.js')}}"></script>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
<style>
    body{
        overflow-x: hidden;
    }
    .card {
        background: #fff;
        border-radius: 2px;
        display: inline-block;
        position: relative;
        width: 99%;
        margin: 20px;
        padding:15px;
    }
    .card-1 {
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }

    .card-1:hover {
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    }

    .card-2 {
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    }

    .card-3 {
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    }

    .card-4 {
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    }

    .card-5 {
        box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);
    }

</style>
@section('content')
<div class="row" style="min-height:550px;">
    @if(count($questions) == 0)
        <br><br>
        <div class="col-md-12">
            <center>
                <h1>¡Obtuviste una calificación perfecta!</h1>
                <h3>¡Felicidades!</h3>
                <img src="{{URL::asset('images/nerd.png')}}" style="width:250px;height:250px;" />
            </center>
        </div>
        <div class="col-md-12" style="margin-top:20px;margin-bottom:20px;">
            <center>
                <a href="{{url('/')}}" class="btn btn-success">Regresar</a>
            </center>
        </div>
    @else
        <div class="col-md-12 col-centered" style="margin-top:50px;">
            <center>
                <h1>¡Ups! Al parecer cometiste algunos errores</h1>
                <img src="{{URL::asset('images/oops.png')}}" style="width:200px;height:200px;"/>
            </center>
        </div>
        @foreach($questions as $key => $question)
            <div class="col-md-10 col-centered">
                <div class="card card-1">
                    <h4>{!!  $questions[$key]  !!}</h4>
                    <h5>Respuesta correcta: {{$right_answers[$key]}}) {{$answers_value_to_display[$key]}}</h5>
                    <h5>Retroalimentación:  {!! $feedbacks[$key] !!}</h5>
                </div>
            </div>
        @endforeach
        <div class="col-md-12" style="margin-top:20px;margin-bottom:20px;">
            <center>
                <a href="{{url('questionnaire/'.$topic_name)}}" class="btn btn-success">Volver a intentarlo</a>
            </center>
        </div>
    @endif
</div>
@endsection

@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-2')
    <script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=AM_CHTML"></script>
    <script src="{{ asset('/js/questionnaire_simulation_provider.js')}}"></script>
    <script src="{{ asset('/js/owl-carousel/owl.carousel.js')}}"></script>
    <script src="{{ asset('/js/owl-carousel/custom.js')}}"></script>
    <script>
        $("img").addClass("img-responsive");
    </script>
@endsection