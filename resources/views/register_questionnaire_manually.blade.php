@extends('layouts.app')
@section('title', 'Registro')

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
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="exampleFormControlSelect1">Número de preguntas por cuestionario.</label>
                    <select class="form-control" id="questions_per_questionnaire" style="width:280px;">
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row" id="questionnaire">
                <u><h4 style="margin-left:15px;">Cuestionario 1</h4></u>
                <div class="col-md-12">
                    <h3>Pregunta 1:</h3>
                    <div class="input_holder">
                        <input class="email_input" style="color:black;border-color:black;" type="search" name="question_1" id="question_1">
                    </div>
                </div>
                <div class="col-md-12 margin-top3">
                    <h3>Retroalimentación</h3>
                    <div class="input_holder">
                        <input class="email_input" style="color:black;border-color:black;" type="search" id="feedback_1">
                    </div>
                </div>
                <div class="col-md-12 margin-top3">
                    <h3>Simulación</h3>
                    <div class="input_holder">
                        <input class="email_input" style="color:black;border-color:black;height:150px;" type="search" id="simulation_1">
                    </div>
                </div>
                <div class="col-md-12 margin-top3" id="options_1">
                    <h3>Opciones</h3>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="options_1" id="inlineRadio1" value="1" checked>
                        <input type="text" class="form-check-label" for="inlineRadio1" id='option_1_1' value="Primera Opción"/>
                    </div>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="options_1" id="inlineRadio2" value="2">
                        <input type="text" class="form-check-label" for="inlineRadio2" id='option_1_2' value="Segunda Opción"/>
                    </div>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="options_1" id="inlineRadio3" value="3">
                        <input type="text" class="form-check-label" for="inlineRadio3" id='option_1_3' value="Tercera Opción"/>
                    </div>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="options_1" id="inlineRadio4" value="4">
                        <input type="text" class="form-check-label" for="inlineRadio4" id='option_1_4' value="Cuarta Opción"/>
                    </div>
                </div>
            </div>
            <form action="{{url('creator/topic/questionnaire/register/manually/save')}}" method="POST" id="finish">
                <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addQuestion">Agregar otra pregunta</button>
                {{ csrf_field() }}
                <input type="submit" class="btn btn-success" style="margin-top:30px;margin-left:50px;" id="saveQuestionnaire" value="Guardar Cuestionario" />
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
        var questions       = 1;
        var simulations     = 1;
        var feedbacks       = 1;
        var options         = [];
        options[0]          = 4;
        var title_id        = 1;
        var numberOfQuestionnaires = $("#questionnaire_number").val();
        var questionsPerQuestionnaire = $("#questions_per_questionnaire").val();
        $("#saveQuestionnaire").hide();
        var requiredQuestions = $("#questionnaire_number").val() * $("#questions_per_questionnaire").val();
        $('#questionnaire_number').change(function(){
            requiredQuestions = $("#questionnaire_number").val() * $("#questions_per_questionnaire").val();
        });
        $('#questions_per_questionnaire').change(function(){
            requiredQuestions = $("#questionnaire_number").val() * $("#questions_per_questionnaire").val();
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
                '                    <h3>Simulación</h3>\n' +
                '                    <div class="input_holder">\n' +
                '                        <input class="email_input" style="color:black;border-color:black;height:150px;" type="search" id="simulation_'+(questions + 1)+'">\n' +
                '                    </div>\n' +
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
            var xmlContent = '<xml cuestionarios="'+numberOfQuestionnaires+'">\n';
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
                xmlContent += '<simulacion>\n';
                xmlContent += $("#simulation_" + i).val();
                xmlContent += '</simulacion>\n';
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
                    window.location.href = "/creator/topics";
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





