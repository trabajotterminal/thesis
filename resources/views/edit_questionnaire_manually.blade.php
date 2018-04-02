@php
    $xmlstring      = file_get_contents('storage/'.$category_name.'/'.$topic_name.'/Cuestionario/changes/cuestionario.xml');
    $xml            = simplexml_load_string($xmlstring);
    $questions      = [];
    $feedbacks      = [];
    $input_images    = [];
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
            array_push($input_images, $bloque -> imagen);
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
                        <h3>Pregunta {{($key + 1)}}:</h3>
                        <div class="input_holder">
                            <input class="email_input" style="color:black;border-color:black;" type="search" name="question_{{($key + 1)}}" id="question_{{($key + 1)}}" value="{{$questions[$key]}}">
                        </div>
                    </div>
                    <div class="col-md-12 margin-top3">
                        <h3>Retroalimentación</h3>
                        <div class="input_holder">
                            <input class="email_input" style="color:black;border-color:black;" type="search" id="feedback_{{($key + 1)}}" value="{{$feedbacks[$key]}}">
                        </div>
                    </div>
                    <div class="col-md-3 margin-top3">
                        <h3>Imágen auxiliar</h3>
                        <input name="input_image" type="file" id="{{($key + 1)}}" onchange="encodeImageFileAsURL(this)">
                        <br>
                        <img src="{{$input_images[$key]}}" id ='preview_image_{{($key + 1)}}' style="width:250px;height:250px;"/>
                    </div>
                    <div class="col-md-12 margin-top3" id="options_{{($key + 1)}}">
                        <h3>Opciones</h3>
                        @foreach($options[$key] as $secondKey => $option)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="options_{{($key + 1)}}" id="inlineRadio{{($key + 1)}}" value="{{($key + 1)}}" checked>
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
    @include('layouts/statics-js-1')
    <script>
        var input_images = [];
        var questions_per_questionnaire_php = 0;
        var number_of_questionnaires_php = 0;
        var numberOfQuestionnaires = 0;
        var questionsPerQuestionnaire = 0;
        var requiredQuestions = 0;
        var questions       = <?php  echo json_decode(count($questions)); ?>;
        var feedbacks       = <?php  echo json_decode(count($feedbacks)); ?>;
        var options         = [];

        function encodeImageFileAsURL(element) {
            var file = element.files[0];
            var id   = parseInt(element.id);
            var reader = new FileReader();
            reader.onloadend = function() {
                input_images[id] = reader.result;
                document.getElementById("preview_image_" + id).src = reader.result;
            }
            reader.readAsDataURL(file);
        }

        $( document ).ready(function() {
            input_images = <? echo json_encode($input_images); ?>;
            input_images.unshift("");
            for(var i = 0; i < input_images.length; i++){
                input_images[i] = Object.values(input_images[i])[0];
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
        $("#addQuestion").click(function(e) {
            var title = "";
            var next  =  title_id * $("#questions_per_questionnaire").val();
            if(questions == next){
                title = '<u><h4 style="margin-left:15px;">Cuestionario '+(++title_id)+'</h4></u>';
            }
            var new_question = '<div class="clearfix" /><br>\n' +
                '                '+title+'<div class="col-md-12">\n' +
                '                    <h3>Pregunta '+(questions + 1)+':</h3>\n' +
                '                    <div class="input_holder">\n' +
                '                        <input class="email_input" style="color:black;border-color:black;" type="search" id="question_'+(questions + 1)+'">\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="col-md-12 margin-top3">\n' +
                '                    <h3>Retroalimentación</h3>\n' +
                '                    <div class="input_holder">\n' +
                '                        <input class="email_input" style="color:black;border-color:black;" type="search" id="feedback_'+(questions +1)+'">\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="col-md-12 margin-top3">\n' +
                '                    <h3>Imágen auxiliar</h3>\n' +
                '                       <input name="input_image" type="file" id="'+(questions + 1)+'" onchange="encodeImageFileAsURL(this)">' +
                '                </div>\n' +
                '                <div class="col-md-12 margin-top3" id="options_'+(questions + 1)+'">\n' +
                '                    <h3>Opciones</h3>\n' +
                '                    <div class="form-check form-check-inline">\n' +
                '                        <input class="form-check-input" type="radio" name="options_'+(questions + 1)+'" id="inlineRadio1" value="1" checked>\n' +
                '                        <input type="text" class="form-check-label" for="inlineRadio1" id="option_'+(questions + 1)+'_1" value="Primera Opción"/>\n' +
                '                    </div>\n' +
                '                    <br>\n' +
                '                    <div class="form-check form-check-inline">\n' +
                '                        <input class="form-check-input" type="radio" name="options_'+(questions + 1)+'" id="inlineRadio2" value="2">\n' +
                '                        <input type="text" class="form-check-label" for="inlineRadio2" id="option_'+(questions + 1)+'_2" value="Segunda Opción"/>\n' +
                '                    </div>\n' +
                '                    <br>\n' +
                '                    <div class="form-check form-check-inline">\n' +
                '                        <input class="form-check-input" type="radio" name="options_'+(questions + 1)+'" id="inlineRadio3" value="3">\n' +
                '                        <input type="text" class="form-check-label" for="inlineRadio3" id="option_'+(questions + 1)+'_3" value="Tercera Opción"/>\n' +
                '                    </div>\n' +
                '                    <br>\n' +
                '                    <div class="form-check form-check-inline">\n' +
                '                        <input class="form-check-input" type="radio" name="options_'+(questions + 1)+'" id="inlineRadio4" value="4">\n' +
                '                        <input type="text" class="form-check-label" for="inlineRadio4" id="option_'+(questions + 1)+'_4" value="Cuarta Opción"/>\n' +
                '                    </div>\n' +
                '                </div>\n';
            ++questions;
            $(new_question).hide().appendTo('#questionnaire').fadeIn();
            if(questions == requiredQuestions){
                $("#saveQuestionnaire").show();
                $("#addQuestion").hide();
            }
            e.preventDefault();
            return 0;
        });

        $("#finish").submit(function(e) {
            var xmlContent = '<xml cuestionarios="'+numberOfQuestionnaires+'" preguntas_por_cuestionario="'+questionsPerQuestionnaire+'">\n';
            xmlContent     += '<cuestionario>\n';
            var questionnaire_id = 1;
            var next       = questionnaire_id * $("#questions_per_questionnaire").val();
            for(var i = 1; i <= questions; i++){
                xmlContent += "<bloque>\n";
                xmlContent += "<pregunta>\n";
                xmlContent += $("#question_" + i).val();
                xmlContent += "</pregunta>\n";
                var answer_id = $('input[name="options_'+i+'"]:checked').val();
                for(var j = 1; j <= 4; j++){
                    if(answer_id == j)
                        xmlContent += '<opcion value="true">\n';
                    else
                        xmlContent += '<opcion value="false">\n';
                    xmlContent += $('#option_'+(i)+'_'+j).val();
                    xmlContent += '</opcion>\n'
                }
                xmlContent += '<imagen>\n';
                xmlContent += input_images[i];
                xmlContent += '\n</imagen>';
                xmlContent += '<retroalimentacion>\n';
                xmlContent += $("#feedback_" + i).val();
                xmlContent += '</retroalimentacion>\n';
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
            xmlContent += '</xml>';
            var url = $('#finish').attr('action');
            var topic_name = $('#hiddenTopicName').val();
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: url,
                type: 'POST',
                data: {"xmlContent": xmlContent, "topic_name": topic_name},
                dataType: 'json',
                success: function( _response ){
                    window.location.href = "/creator/topic/{{$topic_name}}";
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





