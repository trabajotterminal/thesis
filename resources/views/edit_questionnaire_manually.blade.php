@php
    $xmlstring      = file_get_contents('storage/'.$category_name.'/'.$topic_name.'/Cuestionario/changes/cuestionario.xml');
    $xml            = simplexml_load_string($xmlstring);
    $questions      = [];
    $feedbacks      = [];
    $options        = [];
    $options_length = [];
    $options_length[0] = 1;
    $options_length[1] = 2;
    $options_length[2] = 3;
    $options_length[3] = 4;
    $right_answers  = [];
    $tries          = $xml['cuestionarios'];
    $number_of_questionnaires       = $tries;
    $questions_per_questionnaire    = $xml['preguntas_por_cuestionario'];
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
@section('title', 'Edición')
@section('statics-css')
    @include('layouts/statics-css-1')
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ URL::asset('/css/summernote-ext-emoji-ajax.css')}}"  type="text/css" />
    <style>
        .btn-sm {
            width:35px;
            height: 35px;
            padding:3px;
        }
    </style>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <section class="section-less-padding" style="color:black;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3>{{$topic_name}}</h3>
                </div>
                <div class="col-md-4">
                    <label for="exampleFormControlSelect1">Número de cuestionarios.</label>
                    <select class="form-control" id="questionnaire_number" style="width:200px;">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="exampleFormControlSelect1">Número de preguntas por cuestionario.</label>
                    <select class="form-control" id="questions_per_questionnaire" style="width:280px;">
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row" id="questionnaire">
                @foreach($questions as $key => $question)
                    <div class="col-md-12">
                        <h3>Pregunta {{($key + 1)}}: </h3>
                        <div class="col-md-12 margin-top3" style="width:90%;margin-left:-10px;">
                            <div id="question_{{($key + 1)}}"></div>
                        </div>
                        <script>
                            setTimeout(function () {
                                var string = <? echo json_encode(htmlspecialchars_decode($questions[$key])) ?>;
                                $("#question_{{($key + 1)}}").summernote('code', string);
                            }, 1000);
                        </script>
                    </div>
                    <div class="col-md-12 margin-top3">
                        <h3>Retroalimentación</h3>
                        <div class="col-md-12 margin-top3" style="width:90%;margin-left:-10px;">
                            <div id="feedback_{{($key + 1)}}"></div>
                        </div>
                        <script>
                            setTimeout(function () {
                                var string = <? echo json_encode(htmlspecialchars_decode($feedbacks[$key])) ?>;
                                $("#feedback_{{($key + 1)}}").summernote('code', string);
                            }, 1000);
                        </script>
                    </div>
                    <div class="col-md-12 margin-top3" id="options_{{($key + 1)}}">
                        <h3>Opciones</h3>
                        @foreach($options[$key] as $secondKey => $option)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="options_{{($key + 1)}}" id="inlineRadio{{($key + 1)}}" value="{{($secondKey + 1)}}" checked>
                                <input type="text" class="form-check-label" for="inlineRadio{{($key + 1)}}" id='option_{{($key + 1)}}_{{($secondKey + 1)}}' value="{{$option}}"/>
                            </div>
                            <br>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <form action="{{url('creator/topic/questionnaire/update/manually/')}}" method="POST" id="finish">
                <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addQuestion">Agregar otra pregunta</button>
                {{ csrf_field() }}
                <input type="submit" class="btn btn-success" style="margin-top:30px;margin-left:50px;" id="saveQuestionnaire" value="Actualizar cuestionarios" />
                <input type="hidden" value="{{$topic_name}}" id="hiddenTopicName">
            </form>
        </div>
    </section>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-2')
    <script src="{{URL::asset('/ace-builds/src-noconflict/ace.js')}}" type="text/javascript" charset="utf-8"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
    <script src="{{ URL::asset('/js/summernote-es-ES.js')}}"></script>
    <script src="{{ URL::asset('/js/summernote-ext-emoji-ajax.js')}}"></script>
    <script>
        var questions       = <?php  echo json_decode(count($questions)); ?>;
        var feedbacks       = <?php  echo json_decode(count($feedbacks)); ?>;
        $(document).ready(function() {
            questions       = <?php  echo json_decode(count($questions)); ?>;
            feedbacks       = <?php  echo json_decode(count($feedbacks)); ?>;
            var HelloButton = function (context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<b>Σ</b>',
                    tooltip: 'Inserta fórmula',
                    click: function () {
                        context.invoke('editor.insertText', ' `Introduce LaTeX aquí` ');
                    }
                });
                return button.render();   // return button as jquery object
            };
            for(var j = 1; j <= questions; j++){
                $('#question_'+ j).summernote({
                    lang: "es-ES",
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['picture',['picture']],
                        ['link',['link']],
                        ['video',['video']],
                        ['mybutton', ['hello']],
                        ['insert', ['emoji']],
                    ],
                    buttons: {
                        hello: HelloButton
                    },
                    placeholder: 'Introduce el titulo de tu pregunta, también puedes agregar contenido adicional como imágenes o videos.',
                    tabsize: 2,
                    height: 200,
                });

                $('#feedback_' + j).summernote({
                    lang: "es-ES",
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['picture',['picture']],
                        ['link',['link']],
                        ['video',['video']],
                        ['mybutton', ['hello']],
                        ['insert', ['emoji']],
                    ],
                    buttons: {
                        hello: HelloButton
                    },
                    placeholder: 'En caso de que el usuario se equivoque en la pregunta, el texto introducido aquí, será mostrado a modo de retroalimentación.',
                    tabsize: 2,
                    height: 200,
                });
            }
        });
        var questions_per_questionnaire_php     = 0;
        var number_of_questionnaires_php        = 0;
        var numberOfQuestionnaires              = 0;
        var questionsPerQuestionnaire           = 0;
        var requiredQuestions                   = 0;
        var options                             = [];
        var right_answers                       = [];
        $( document ).ready(function() {
            right_answers = <?php echo json_encode($right_answers); ?>;
            for(var i = 0; i < right_answers.length; i++){
                var string = "input:radio[name=options_"+(i+1)+"]:nth("+(right_answers[i] - 1)+")";
                $(string).attr('checked',true);
            }
            questions_per_questionnaire_php = <?php echo json_decode($questions_per_questionnaire); ?>;
            number_of_questionnaires_php = <?php echo json_decode($number_of_questionnaires); ?>;
            $("#questions_per_questionnaire").val(questions_per_questionnaire_php);
            $("#questionnaire_number").val(number_of_questionnaires_php);
            numberOfQuestionnaires = number_of_questionnaires_php;
            questionsPerQuestionnaire = questions_per_questionnaire_php;
            requiredQuestions = numberOfQuestionnaires * questionsPerQuestionnaire;
            $('#addQuestion').hide();
            $('#finish').hide();
            if(questions < requiredQuestions){
                $('#addQuestion').show();
            }else{
                $('#finish').show();
            }
        });
        for(var i = 0; i < questions; i++){
            options[i] = 4;
        }
        var title_id        = 1;
        $('#questionnaire_number').change(function(){
            numberOfQuestionnaires = $("#questionnaire_number").val();
            requiredQuestions = $("#questionnaire_number").val() * $("#questions_per_questionnaire").val();
            if(questions < requiredQuestions){
                $("#saveQuestionnaire").hide();
                $('#addQuestion').show();
            }
        });
        $('#questions_per_questionnaire').change(function(){
            questionsPerQuestionnaire = $("#questions_per_questionnaire").val();
            requiredQuestions = $("#questionnaire_number").val() * $("#questions_per_questionnaire").val();
            if(questions < requiredQuestions){
                $("#saveQuestionnaire").hide();
                $('#addQuestion').show();
            }
        });


        $("#finish").submit(function(e) {
            var xmlContent = '<contenido cuestionarios="'+numberOfQuestionnaires+'" preguntas_por_cuestionario="'+questionsPerQuestionnaire+'">\n';
            xmlContent     += '<cuestionario>\n';
            var questionnaire_id = 1;
            var next       = questionnaire_id * $("#questions_per_questionnaire").val();
            for(var i = 1; i <= questions; i++){
                xmlContent += "<bloque>\n";
                xmlContent += "<pregunta><![CDATA[";
                xmlContent += $('#question_'+(i)).summernote('code') + '\n';
                xmlContent += " ]]></pregunta>\n";
                xmlContent += '<retroalimentacion><![CDATA[\n';
                xmlContent += $('#feedback_'+(i)).summernote('code') + '\n';
                xmlContent += ']]></retroalimentacion>\n';
                var answer_id = $('input[name="options_'+i+'"]:checked').val();
                console.warn(answer_id);
                for(var j = 1; j <= 4; j++){
                    if(answer_id == j)
                        xmlContent += '<opcion value="true">\n';
                    else
                        xmlContent += '<opcion value="false">\n';
                    xmlContent += $('#option_'+(i)+'_'+j).val();
                    xmlContent += '</opcion>\n'
                }
                xmlContent += "</bloque>\n";
                if(next === i){
                    if(i === questions){
                        xmlContent += '</cuestionario>\n';
                    }else{
                        xmlContent += '</cuestionario>\n<cuestionario>\n';
                    }
                    next = ++questionnaire_id * $("#questions_per_questionnaire").val();
                }
            }
            xmlContent += '</contenido>';
            var url = $('#finish').attr('action');
            var topic_name = $('#hiddenTopicName').val();
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: '{{URL('creator/topic/questionnaire/update/manually/')}}',
                type: 'POST',
                data: {"xmlContent": xmlContent, "topic_name": topic_name},
                dataType: 'json',
                success: function( _response ){
                    window.location.href = "{{URL('/creator/topic/'. $topic_name)}}";
                },
                error: function(xhr, status, error) {
                    alert(error);
                },
            });
            e.preventDefault();
            return 0;
        });
    </script>
@endsection





