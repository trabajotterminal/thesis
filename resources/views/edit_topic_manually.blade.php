@php
        function getSubstringArray($needle, $text){
            $lastPos    = 0;
            $array      = [];
            while (($lastPos = strpos($text, $needle, $lastPos)) !== false){
                $array[] = $lastPos;
                $lastPos = $lastPos + strlen($needle);
            }
            return $array;
        }

        $xml_path           = $xmlFilePath[0];
        $xml_path           = substr($xml_path, strpos($xml_path, '/') + 1);
        $text               = file_get_contents("storage/".$xml_path);
        $title_opening      = [];
        $title_closing      = [];
        $title_content      = [];
        $subtitle_opening   = [];
        $subtitle_closing   = [];
        $subtitle_content   = [];
        $paragraph_opening  = [];
        $paragraph_closing  = [];
        $paragraph_content  = [];
        $code_opening       = [];
        $code_closing       = [];
        $code_content       = [];
        $code_tag_end       = [];
        $reference_title_opening = [];
        $reference_title_closing = [];
        $reference_title_content = [];
        $reference_link_opening  = [];
        $reference_link_closing  = [];
        $reference_link_content  = [];
        $text = str_replace(']]>', '', $text);
        $text = str_replace('<![CDATA[', '', $text);
        $title_opening          = getSubstringArray('<titulo>', $text);
        $title_closing          = getSubstringArray('</titulo>', $text);
        $subtitle_opening       = getSubstringArray('<subtitulo>', $text);
        $subtitle_closing       = getSubstringArray('</subtitulo>', $text);
        $paragraph_opening      = getSubstringArray('<parrafo><![CDATA[', $text);
        $paragraph_closing      = getSubstringArray('</parrafo>', $text);
        $code_opening           = getSubstringArray("<codigo>", $text);
        $code_closing           = getSubstringArray('</codigo>', $text);
        $reference_title_opening = getSubstringArray('<encabezado>', $text);
        $reference_title_closing = getSubstringArray('</encabezado>', $text);
        $reference_link_opening  = getSubstringArray('<link>', $text);
        $reference_link_closing  = getSubstringArray('</link>', $text);
        for($i = 0; $i < count($title_opening); $i++){
            $title_content[$i] = substr($text, $title_opening[$i] + strlen('<titulo>'), $title_closing[$i] - $title_opening[$i] - strlen('</titulo>') + 1);
        }
        for($i = 0; $i < count($subtitle_opening); $i++){
            $subtitle_content[$i] = substr($text, $subtitle_opening[$i] + strlen('<subtitulo>'), $subtitle_closing[$i] - $subtitle_opening[$i] - strlen('</subtitulo>') + 1);
        }
        for($i = 0; $i < count($paragraph_opening); $i++){
            $paragraph_content[$i] = substr($text, $paragraph_opening[$i] + strlen('<parrafo>'), $paragraph_closing[$i] - $paragraph_opening[$i] -  strlen('</parrafo>') + 1);
        }
        for($i = 0; $i < count($code_opening); $i++){
            $code_content[$i] = substr($text, $code_opening[$i] + strlen('<codigo>'), $code_closing[$i] - $code_opening[$i] -  strlen('</codigo>') + 1);
        }
        for($i = 0; $i < count($reference_title_opening); $i++){
            $reference_title_content[$i] = substr($text, $reference_title_opening[$i] + strlen('<encabezado>'), $reference_title_closing[$i] - $reference_title_opening[$i] -  strlen('</encabezado>') + 1);
        }

        for($i = 0; $i < count($reference_link_opening); $i++){
            $reference_link_content[$i] = substr($text, $reference_link_opening[$i] + strlen('<link>'), $reference_link_closing[$i] - $reference_link_opening[$i] -  strlen('</link>') + 1);
        }
        $indexOpeningContent    = array_merge($title_opening, $subtitle_opening, $paragraph_opening, $code_opening, $reference_title_opening, $reference_link_opening);
        sort($indexOpeningContent);
        $titles        = 0;
        $subtitles     = 0;
        $paragraphs    = 0;
        $codes         = 0;
        $references    = 0;
@endphp
@extends('layouts.app')
@section('title', 'Edición.')
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
    <style>
        body{
            overflow-x: hidden;
        }
    </style>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <section class="section-less-padding" style="color:black;">
        <div class="container">
            <h3>{{$topic_name}} / Teoría</h3>
            <br>
            <div class="row" id="theory">
                <div class="col-md-12">
                    @foreach($indexOpeningContent as $key => $value)
                            @if(in_array($value, $title_opening))
                                <div class="col-md-12">
                                    <h3>Titulo</h3>
                                    <div class="input_holder">
                                        <input class="email_input" style="color:black;border-color:black;" type="search" name="title" value="{{$title_content[$titles++]}}">
                                    </div>
                                </div>
                            @endif
                            @if(in_array($value, $subtitle_opening))
                                <div class="col-md-12 margin-top3">
                                    <h3>Subtitulo</h3>
                                    <div class="input_holder">
                                        <input class="email_input" style="color:black;border-color:black;" type="search" value="{{$subtitle_content[$subtitles]}}" name="subtitle_{{++$subtitles}}">
                                    </div>
                                </div>
                                @if($key > 2)
                                    <script>
                                        setTimeout(function () {
                                            elements.push('subtitle');
                                        }, 1000);
                                    </script>
                                @endif
                            @endif
                            @if(in_array($value, $paragraph_opening))
                                <div class="col-md-12 margin-top3" style="width:87%;">
                                    <h3>Parrafo</h3>
                                    <div id="paragraph_{{++$paragraphs}}" ></div>
                                </div>
                                <script>
                                    setTimeout(function () {
                                        var string = <?php echo json_encode($paragraph_content[$paragraphs - 1]) ?>;
                                        $("#paragraph_{{$paragraphs}}").summernote("code", string);
                                    }, 1000);
                                </script>
                                @if($key > 2)
                                    <script>
                                        setTimeout(function () {
                                            elements.push('paragraph');
                                        }, 1000);
                                    </script>
                                @endif
                            @endif
                            @if(in_array($value, $code_opening))
                                <div class="col-md-12 margin-top3">
                                    <h3> Código</h3>
                                    <div id="code_{{++$codes}}" style="width:87%;height:auto; line-height:1px;"></div>
                                </div>
                                <script>
                                    setTimeout(function () {
                                        var string = <?php echo json_encode($code_content[$codes - 1]) ?>;
                                        editors[{{$codes}}].setValue(string);
                                    }, 1000);
                                </script>
                                @if($key > 2)
                                    <script>
                                        setTimeout(function () {
                                            elements.push('code');
                                        }, 1000);
                                    </script>
                                @endif
                            @endif
                    @endforeach
                </div>
            </div>
            <div class="row" id="references">
                @foreach($indexOpeningContent as $key => $value)
                    @if(in_array($value, $reference_title_opening))
                        <div class="col-md-12">
                            <h3>Referencia</h3>
                            <form>
                                <div class="form-group" style="width:87%;">
                                    <label for="reference_title_{{($references + 1)}}">Titulo</label>
                                    <input type="search" class="form-control" name="reference_title_{{$references + 1}}" value="{{$reference_title_content[$references]}}">
                                </div>
                                <div class="form-group" style="width:87%">
                                    <label for="reference_link_{{($references + 1)}}">Link</label>
                                    <input type="search" class="form-control" name="reference_link_{{$references + 1}}" value="{{$reference_link_content[$references]}}">
                                </div>
                            </form>
                        </div>
                        @php
                            ++$references;
                        @endphp
                        @if($references > 1)
                            <script>
                                setTimeout(function () {
                                    elements.push('reference');
                                }, 1000);
                            </script>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addSubtitle">Agregar nuevo subtitulo</button>
                <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addParagraph">Agregar nuevo parrafo</button>
                <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addCode">Agregar código</button>
                <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addReference">Agregar referencia</button>
                <form action="{{url('creator/topic/theory/update/manually')}}" method="POST" id="finish">
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-success" style="margin-top:30px;margin-left:50px;"  value="Actualizar teoría" />
                    <input type="hidden" value="{{$topic_name}}" id="hiddenTopicName">
                </form>
            </div>
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
        var title       = 0;
        var subtitle    = 0;
        var paragraph   = 0;
        var code        = 0;
        var reference   = 0;
        var elements = ['title', 'subtitle', 'paragraph', 'reference'];
        var editors     = [];
        var HelloButton = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<b>Σ</b>',
                tooltip: 'Inserta fórmula',
                click: function () {
                    context.invoke('editor.insertText', ' `Introduce LaTeX aquí` ');
                }
            });
            return button.render();
        };
        $(document).ready(function() {
            title       = <?php echo json_decode($titles);      ?>;
            subtitle    = <?php echo json_decode($subtitles);   ?>;
            paragraph   = <?php echo json_decode($paragraphs);  ?>;
            code        = <?php echo json_decode($codes);       ?>;
            reference   = <?php echo json_decode($references);  ?>;
            console.warn(elements);
            for(var i = 1; i <= code; i++){
                editors[i] = CodeMirror(document.getElementById("code_"+(i)), {
                    lineNumbers: true,
                    mode: "javascript",
                    keyMap: "sublime",
                    autoCloseBrackets: true,
                    matchBrackets: true,
                    showCursorWhenSelecting: true,
                    theme: "monokai",
                    tabSize: 2
                });
            }
            for(var j = 1; j <= paragraph; j++){
                $('#paragraph_'+ j).summernote({
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
            }
        });

        $("#addSubtitle").click(function() {
            var elm = '<div class="col-md-12 margin-top3">\n' +
                '                    <h3>Subtitulo</h3>\n' +
                '                    <div class="input_holder">\n' +
                '                        <input class="email_input" style="color:black;border-color:black;" type="search" name="subtitle_'+(++subtitle)+'">\n' +
                '                    </div>\n' +
                '                </div>';
            $(elm).hide().appendTo('#theory').fadeIn();
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

            $(elm).hide().appendTo('#theory').fadeIn();
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
            $(elm).hide().appendTo('#theory').fadeIn();
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
            var xmlContent = "<teoria>";
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
                    xmlContent += '<codigo><![CDATA[\n';
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
                url: '{{URL('creator/topic/theory/update/manually')}}',
                type: 'POST',
                data: {"xmlContent": xmlContent, "topic_name": topic_name},
                dataType: 'json',
                success: function( _response ){
                    window.location.href = "{{URL('/creator/topic/'.$topic_name)}}";
                },
                error: function(xhr, status, error) {
                    alert(error);
                },
            });
            return 0;
        });
    </script>
@endsection





