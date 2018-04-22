@extends('layouts.app')
@section('title', 'Registro')

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
                    <h3>Pregunta 1</h3>
                    <div class="col-md-12 margin-top3" style="width:90%;margin-left:-10px;">
                        <div id="question_1"></div>
                    </div>
                </div>
                <div class="col-md-12 margin-top3">
                    <h3>Retroalimentación</h3>
                    <div class="col-md-12 margin-top3" style="width:90%;margin-left:-10px;">
                        <div id="feedback_1"></div>
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
    @include('layouts/statics-js-2')
    <script src="/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
    <script src="{{ URL::asset('/js/summernote-es-ES.js')}}"></script>
    <script src="{{ URL::asset('/js/summernote-ext-emoji-ajax.js')}}"></script>
    <script>
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
        $(document).ready(function() {
            $('#question_1').summernote({
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

            $('#feedback_1').summernote({
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
        });

        var input_images;
        $(document).ready(function(){
            input_images = [];
        });
        function encodeImageFileAsURL(element) {
            var file = element.files[0];
            var id   = parseInt(element.id);
            var reader = new FileReader();
            reader.onloadend = function() {
                input_images[id] = reader.result;
                document.getElementById("preview_image_" + id).src = reader.result;
                $('#preview_image_'+id).fadeIn(2000).css('display','block');
            }
            reader.readAsDataURL(file);
        }
        var questions       = 1;
        var simulations     = 1;
        var feedbacks       = 1;
        var options         = [];
        options[0]          = 4;
        var title_id        = 1;
        var numberOfQuestionnaires = $("#questionnaire_number").val();
        var questionsPerQuestionnaire = $("#questions_per_questionnaire").val();
        var requiredQuestions = $("#questionnaire_number").val() * $("#questions_per_questionnaire").val();
        $("#saveQuestionnaire").hide();
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
            var preview_variable = "preview_image_" + (questions + 1);
            var new_question = '<div class="clearfix" /><br>\n' +
                '                    <h3>Pregunta '+(questions + 1)+':</h3>\n' +
                '                '+title+'<div class="col-md-12 margin-top3">' +
                '                           <div id="question_'+(questions + 1)+'" style="width:90%;margin-left:-10px;"></div> ' +
                '                </div>\n' +
                '                <div class="col-md-12 margin-top3">\n' +
                '                    <h3>Retroalimentación</h3>\n' +
                '                           <div id="feedback_'+(questions + 1)+'" style="width:90%;margin-left:-10px;"></div> ' +
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
            $(new_question).hide().appendTo('#questionnaire').fadeIn();
            $('#question_'+(questions + 1)).summernote({
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
                placeholder: "Introduce el titulo de tu pregunta, también puedes agregar contenido adicional como imágenes o videos.",
                tabsize: 2,
                height: 200,
            });
            $('#feedback_'+(questions + 1)).summernote({
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
            ++questions;
            if(questions == requiredQuestions){
                $("#saveQuestionnaire").show();
                $("#addQuestion").hide();
            }
            e.preventDefault();
            return 0;
        });

        $("#finish").submit(function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
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
            return 0;
        });

    </script>
@endsection





