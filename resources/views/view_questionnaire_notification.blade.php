@php
    $xmlstring      = file_get_contents('storage/'.$category_name.'/'.$topic_name.'/Cuestionario/changes/cuestionario.xml');
    $xml            = simplexml_load_string($xmlstring);
    $questions      = [];
    $feedbacks      = [];
    $input_images   = [];
    $options        = [];
    $right_answers  = [];
    $tries          = $xml['cuestionarios'];
    $i = 0;
    for($k = 0; $k < $tries; $k++){
        foreach($xml->children()[$k] as $index => $bloque) {
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
@section('title', 'Revisión.')
@section('statics-css')
    @include('layouts/statics-css-2')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/js/smart-forms/smart-forms.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/js/masterslider/style/masterslider.css')}}" />
    <link href="{{URL::asset('/js/owl-carousel/owl.carousel.css')}}" rel="stylesheet">
    <script src="{{ asset('/js/cytoscape.js')}}"></script>
    <style>
        body{
            overflow-x: hidden;
            color: black;
        }
        .no-margin {
            margin: 0px !important;
        }
    </style>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <h4 class="margin-left-5 margin-top3">Cuestionario / {{$topic_name}}</h4>
    <div class="carousel_holder">
        <div id="owl-demo7" class="owl-carousel" style="min-height: calc(100vh - 163px);">
            @foreach($questions as $key => $question)
                <div class="item">
                    <div class="row">
                        <div class="col-md-12" style="min-height: calc(100vh - 163px);">
                            <div class="row no-margin" style="height:auto; margin-bottom:0px;padding-left:30px;padding-right:50px;">
                                {!! $question !!}
                            </div>
                            <div class="row no-margin" style="margin-left:100px;">
                                <center>
                                @foreach($options[$key] as $secondKey => $option)
                                    <div class="col-md-3">
                                        <div class="form-check" id="{{$key}}">
                                            <input class="form-check-input" type="radio" name="options_{{($key + 1)}}" value="">
                                            <label class="form-check-label" for="options_{{($key + 1)}}">
                                                <h5>{{$option}}</h5>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                                </center>
                            </div>
                        </div>
                        <div class="col-md-6 bmargin">

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row margin-top3" style="margin-bottom:30px;">
        <div class="col-md-12">
            <center>
                <div class="form-group" style="margin:0px;padding:0px;">
                    <form action="{{url('admin/notification/questionnaire/resolve')}}" id="notificationForm" method="POST">
                        <textarea class="form-control" rows="2" style="width:450px;" name="comment" placeholder="Escribe una retroalimentación para {{$creator_username}}."></textarea>
                        <br>
                        <input type="submit" name="accept" class="btn btn-success" value="Aceptar cuestionario">
                        <input type="submit" name="decline" class="btn btn-danger" style="margin-left:30px;" value="Rechazar cuestionario">
                    </form>
                </div>
            </center>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-2')
    <script type="text/javascript" async src="{{ asset('/js/mathjax.js?config=AM_CHTML')}}"></script>
    <script src="{{ asset('/js/questionnaire_simulation_provider.js')}}"></script>
    <script src="{{ asset('/js/owl-carousel/owl.carousel.js')}}"></script>
    <script src="{{ asset('/js/owl-carousel/custom.js')}}"></script>
    <script>
        $("img").addClass("img-responsive");
        var action  = "";
        var message = $('textarea[name="comment"]').val();
        var notification_id = <?php echo json_encode($notification -> id) ?>;
        var url = $("#notificationForm").attr('action');
        $("#notificationForm").submit(function(e){
            message = $('textarea[name="comment"]').val();
            e.preventDefault();
            e.stopImmediatePropagation();
            $.ajax ({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                type: 'POST',
                data: {'notification_id': notification_id, 'action': action.name, 'message': message},
                url: '{{URL('admin/notification/questionnaire/resolve')}}',
                datatype: "json",
                success: function(data) {
                    window.location = "{{URL('/')}}";
                }
            });
            return false;
        });

        $(".btn").click(function(e){
            action = this;
        });
    </script>
@endsection
