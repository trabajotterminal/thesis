@php
    $xmlstring      = file_get_contents('storage/'.$category_name.'/'.$topic_name.'/Cuestionario/latest/cuestionario.xml');
    $try_number     = $tries;
    $xml            = simplexml_load_string($xmlstring);
    $questions      = [];
    $feedbacks      = [];
    $input_images   = [];
    $options        = [];
    $right_answers  = [];
    $i = 0;
    $shouldCount = 0;
    if($tries < $xml['cuestionarios']){
        $shouldCount = 1;
        foreach($xml->children()[$tries] as $index => $bloque) {
            array_push($questions, $bloque -> pregunta);
            array_push($feedbacks, $bloque -> retroalimentacion);
            $option_list = [];
            $j = 0;
            foreach($bloque -> opcion  as $option){
                array_push($option_list, $option);
                if($option['value'] == 'true'){
                    $right_answers[$i] = $j + 1;
                }
                ++$j;
            }
            array_push($options, $option_list);
            ++$i;
        }
    }else{
        foreach($xml -> children()[count($xml -> children()) - 1] as $index => $bloque) {
            array_push($questions, $bloque -> pregunta);
            array_push($feedbacks, $bloque -> retroalimentacion);
            $option_list = [];
            $j = 0;
            foreach($bloque -> opcion  as $option){
                array_push($option_list, $option);
                if($option['value'] == 'true'){
                    $right_answers[$i] = $j + 1;
                }
                ++$j;
            }
            array_push($options, $option_list);
            ++$i;
        }
    }
@endphp
@extends('layouts.app')
@section('title', 'Cuestionario')
@section('statics-css')
    @include('layouts/statics-css-2')
    <style>
        body{
            color:black;
        }
        .no-margin {
            margin: 0px !important;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/js/smart-forms/smart-forms.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/js/masterslider/style/masterslider.css')}}" />
    <link href="{{URL::asset('/js/owl-carousel/owl.carousel.css')}}" rel="stylesheet">
    <script src="{{ asset('/js/cytoscape.js')}}"></script>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <center><h3 class="uppercase weight1 pull margin-top1">{{$topic_name}}</h3></center>
    <div class="carousel_holder" id="questionnaire" style="overflow-x: hidden;">
        <div id="owl-demo7" class="owl-carousel" style="min-height:540px;">
            @foreach($questions as $key => $question)
                <div class="item">
                    <div class="row">
                        <div class="col-md-12" style="height:min-height:450px">
                            <div class="row no-margin">
                                    {!!  $question !!}
                            </div>
                            <div class="row no-margin">
                                <center>
                                    @foreach($options[$key] as $secondKey => $option)
                                    <div class="col-md-3">
                                        <div class="form-check" id="{{$key}}">
                                            <input class="form-check-input" type="radio" name="options_{{($key + 1)}}" value="{{($secondKey + 1)}}" {{$secondKey == 0 ? 'checked': ''}}>
                                            <label class="form-check-label" for="options_{{($key + 1)}}">
                                                <h5>{{$option}}</h5>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </center>
                            </div>
                        </div>
                        @if($key + 1 == count($questions))
                            <div class="row">
                                <form action="{{url('/questionnaire/'.$topic_name.'/evaluate')}}" method="POST" id="questionnaireDone" name="questionnaireDone">
                                    {!! csrf_field() !!}
                                    <input type="hidden" id="numberOfQuestions" value="{{count($questions)}}">
                                    <input type="hidden" name="topic_name" value="{{$topic_name}}">
                                    <input type="hidden" name="shouldCount" value="{{$shouldCount}}">
                                    <input type="hidden" name="tries" value="{{$tries}}">
                                    <input type="submit" class="btn btn-success" style="float:right; margin-right:100px;" value="Terminar cuestionario" />
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            @if(count($questions) == 0)
                    <div class="row" style="margin-top:200px;">
                        <div class="col-md-6 text-center" style="float: none; margin: 0 auto;">
                            <h4>Has agotado los intentos para este tema, vuelve más tarde.</h4>
                        </div>
                    </div>
            @endif
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-2')
    <script type="text/javascript" async src="{{ asset('/js/mathjax.js')}}"></script>
    <script src="{{ asset('/js/questionnaire_simulation_provider.js')}}"></script>
    <script src="{{ asset('/js/owl-carousel/owl.carousel.js')}}"></script>
    <script src="{{ asset('/js/owl-carousel/custom.js')}}"></script>
    <script>
        var answers     = [];
        var questions   = [];
        var feedbacks   = [];
        $("img").addClass("img-responsive");
        $("#questionnaireDone").submit(function(e) {
            var number_of_questions = $('#numberOfQuestions').val();
            for(i = 0; i < number_of_questions; i++) {
                $('#questionnaireDone').append('<input type="hidden" name="user_answers[]" value="'+$('input[name="options_'+(i+1)+'"]:checked').val()+'" />');
            }
        });
    </script>
@endsection
