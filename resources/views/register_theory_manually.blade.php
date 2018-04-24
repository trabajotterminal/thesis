@extends('layouts.app')

@section('title', 'Registro')

@section('statics-css')
    @include('layouts/statics-css-1')

    <link rel="stylesheet" href="{{ URL::asset('/css/codemirror.css')}}"  type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('/css/monokai.css')}}"  type="text/css" />
    <script src="{{ URL::asset('/js/codemirror.js')}}"></script>
    <script src="{{ URL::asset('/js/matchbrackets.js')}}"></script>
    <script src="{{ URL::asset('/js/closebrackets.js')}}"></script>
    <script src="{{ URL::asset('/js/javascript.js')}}"></script>
    <script src="{{ URL::asset('/js/sublime.js')}}"></script>
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ URL::asset('/css/summernote-ext-emoji-ajax.css')}}"  type="text/css" />

@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <section class="section-less-padding" style="color:black;">
        <div class="container">
            <h3>{{$topic_name}} / Teoría</h3>
            <br>
            <div class="row" id="questionnaire">
                <div class="col-md-12">
                    <h3>Titulo</h3>
                    <div class="input_holder">
                        <input class="email_input" style="color:black;border-color:black;" type="search" name="title">
                    </div>
                </div>
                <div class="col-md-12 margin-top3">
                    <h3>Subtitulo</h3>
                    <div class="input_holder">
                        <input class="email_input" style="color:black;border-color:black;" type="search" name="subtitle_1">
                    </div>
                </div>
                <div class="col-md-12 margin-top3" style="width:87%;">
                    <h3>Parrafo</h3>
                    <div id="paragraph_1"></div>
                </div>
            </div>
            <div class="row" id="references">
                <div class="col-md-12">
                    <h3>Referencia</h3>
                    <form>
                        <div class="form-group" style="width:87%;">
                            <label for="reference_title_1">Titulo</label>
                            <input type="search" class="form-control" name="reference_title_1" placeholder="Guide to competitive programming. Pg: 75-104">
                        </div>
                        <div class="form-group" style="width:87%">
                            <label for="reference_link_1">Link</label>
                            <input type="search" class="form-control" name="reference_link_1" placeholder="https://books.google.com.mx/books?id=nCdFDwAAQBAJ&printsec=frontcover&dq=competitive+programming&hl=en&sa=X&ved=0ahUKEwirjuzpwa3aAhVEcq0KHZI9A3UQ6wEIKjAA#v=onepage&q=competitive%20programming&f=false">
                        </div>
                    </form>
                </div>
            </div>
            <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addSubtitle">Agregar nuevo subtitulo</button>
            <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addParagraph">Agregar nuevo parrafo</button>
            <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addCode">Agregar código</button>
            <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addReference">Agregar referencia</button>
            <form action="{{url('creator/topic/theory/register/manually/save')}}" method="POST" id="finish">
                {{ csrf_field() }}
                <input type="submit" class="btn btn-success" style="margin-top:30px;margin-left:50px;"  value="Finalizar teoría" />
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
            $('#paragraph_1').summernote({
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
                placeholder: 'Introduce tu párrafo',
                tabsize: 2,
                height: 200,
            });
        });
    </script>
    <script>
        var elements = ['title', 'subtitle', 'paragraph', 'reference'];
        var title       = 1;
        var subtitle    = 1;
        var paragraph   = 1;
        var reference   = 1;
        var code        = 0;
        var editors     = [];
        $("#addSubtitle").click(function() {
            var elm = '<div class="col-md-12 margin-top3">\n' +
                '                    <h3>Subtitulo</h3>\n' +
                '                    <div class="input_holder">\n' +
                '                        <input class="email_input" style="color:black;border-color:black;" type="search" name="subtitle_'+(++subtitle)+'">\n' +
            '                    </div>\n' +
            '                </div>';
            $(elm).hide().appendTo('#questionnaire').fadeIn();
            elements.push('subtitle');
        });

        $('#addReference').click(function(){
            var elm = '<div class="col-md-12">' +
                '                    <h3>Referencia</h3>' +
                '                    <form>' +
                '                        <div class="form-group" style="width:87%;">' +
                '                            <label for="reference_title_'+(reference + 1)+'">Titulo</label>' +
                '                            <input type="search" class="form-control" name="reference_title_'+(reference + 1)+'" placeholder="Guide to competitive programming. Pg: 75-104">' +
                '                        </div>\n' +
                '                        <div class="form-group" style="width:87%">' +
                '                            <label for="reference_link_'+(reference + 1)+'">Link</label>' +
                '                            <input type="search" class="form-control" name="reference_link_'+(++reference)+'" placeholder="https://books.google.com.mx/books?id=nCdFDwAAQBAJ&printsec=frontcover&dq=competitive+programming&hl=en&sa=X&ved=0ahUKEwirjuzpwa3aAhVEcq0KHZI9A3UQ6wEIKjAA#v=onepage&q=competitive%20programming&f=false">' +
                '                        </div>' +
                '                    </form>' +
                '                </div>';
            $(elm).hide().appendTo('#references').fadeIn();
            elements.push('reference');
        });

        $("#addParagraph").click(function() {
            var elm = '<div class="col-md-12 margin-top3" style="width:87%;">\n' +
                '                    <h3>Parrafo</h3>\n' +
                '                    <div id="paragraph_'+(paragraph + 1)+'"></div>' +
                '      </div>';

            $(elm).hide().appendTo('#questionnaire').fadeIn();
            $('#paragraph_'+(++paragraph)).summernote({
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
                placeholder: 'Introduce tu párrafo',
                tabsize: 2,
                height: 200,
            });
            elements.push('paragraph');
        });

        $("#addCode").click(function() {
            var elm =   '<div class="col-md-12 margin-top3">\n'   +
                            '<h3> Código</h3>' +
                            '<div id="code_'+(code + 1)+'" style="width:87%;height:auto; line-height:1px;">' +
                        '</div>';
            $(elm).hide().appendTo('#questionnaire').fadeIn();
            editors[++code] = CodeMirror(document.getElementById("code_"+(code)), {
                lineNumbers: true,
                mode: "javascript",
                keyMap: "sublime",
                autoCloseBrackets: true,
                matchBrackets: true,
                showCursorWhenSelecting: true,
                theme: "monokai",
                tabSize: 2
            });
            elements.push('code');
        });


        $("#finish").submit(function(e) {
            e.preventDefault();
            var p = 0;
            var c = 0;
            var t = 0;
            var s = 0;
            var r = 0;
            var left = 0;
            var xmlContent = "";
            xmlContent += "<teoria>";
            for(var i = 0; i < elements.length; i++) {
                if (elements[i] == 'title') {
                    xmlContent += '<titulo>\n';
                    xmlContent += $('input[name=title]').val();
                    xmlContent += '</titulo>\n'
                }
                if (elements[i] == 'subtitle') {
                    xmlContent += '<subtitulo>\n';
                    xmlContent += $('input[name=subtitle_' + (++s) + ']').val() + '\n';
                    xmlContent += '</subtitulo>\n'
                }
                if (elements[i] == 'paragraph') {
                    xmlContent += '<parrafo><![CDATA[\n';
                    xmlContent += $('#paragraph_'+(++p)).summernote('code') + '\n';
                    xmlContent += ']]></parrafo>\n'
                }
                if (elements[i] == 'code') {
                    xmlContent += '<codigo lenguaje="c++"><![CDATA[\n';
                    xmlContent += editors[++c].getValue();
                    xmlContent += ']]></codigo>\n'
                }
                if(elements[i] == 'reference'){
                   ++left;
                }
            }
            for(var i = 0; i < left; i++){
                xmlContent += '<referencia>\n';
                xmlContent += '<encabezado>\n';
                xmlContent += $('input[name=reference_title_' + (r + 1) + ']').val() + '\n';
                xmlContent += '</encabezado>\n';
                xmlContent += '<link>\n';
                xmlContent += $('input[name=reference_link_' + (++r) + ']').val() + '\n';
                xmlContent += '</link>\n';
                xmlContent += '</referencia>\n';
            }
            xmlContent += "</teoria>";
            var url = $('#finish').attr('action');
            var topic_name = $('#hiddenTopicName').val();
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: '{{URL('creator/topic/theory/register/manually/save')}}',
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
            return 0;
        });

    </script>
@endsection





