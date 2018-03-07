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
                <div class="col-md-12 margin-top3">
                    <h3>Parrafo</h3>
                    <div class="input_holder">
                        <input class="email_input" style="color:black;border-color:black;height:200px;" type="search" name="paragraph_1">
                    </div>
                </div>
            </div>
            <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addSubtitle">Agregar nuevo subtitulo</button>
            <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addParagraph">Agregar nuevo parrafo</button>
            <button class="btn btn-light" style="margin-top:30px;margin-left:50px;" id="addCode">Agregar código</button>
            <form action="{{url('admin/topic/theory/register/manually/save')}}" method="POST" id="finish">
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
    @include('layouts/statics-js-1')
    <script>
        var elements = ['title', 'subtitle', 'paragraph'];
        var title       = 1;
        var subtitle    = 1;
        var paragraph   = 1;
        var code        = 0;
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

        $("#addParagraph").click(function() {
            var elm = '<div class="col-md-12 margin-top3">\n' +
                '                    <h3>Parrafo</h3>\n' +
                '                    <div class="input_holder">\n' +
                '                        <input class="email_input" style="color:black;border-color:black;height:200px;" type="search" name="paragraph_'+(++paragraph)+'">\n' +
                '                    </div>\n' +
                '                </div>';
            $(elm).hide().appendTo('#questionnaire').fadeIn();
            elements.push('paragraph');
        });

        $("#addCode").click(function() {
            var elm = '<div class="col-md-12 margin-top3">\n' +
                '                    <h3>Codigo</h3>\n' +
                '                    <div class="input_holder">\n' +
                '                        <input class="email_input" style="color:black;border-color:black;height:350px;" type="search" name="code_'+(++code)+'">\n' +
                '                    </div>\n' +
                '                </div>';
            $(elm).hide().appendTo('#questionnaire').fadeIn();
            elements.push('code');
        });


        $("#finish").submit(function(e) {
            e.preventDefault();
            var p = 0;
            var c = 0;
            var t = 0;
            var s = 0;
            var xmlContent = "";
            for(let i = 0; i < elements.length; i++) {
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
                    xmlContent += '<parrafo>\n';
                    xmlContent += $('input[name=paragraph_' + (++p) + ']').val() + '\n';
                    xmlContent += '</parrafo>\n'
                }
                if (elements[i] == 'code') {
                    xmlContent += '<codigo>\n';
                    xmlContent += $('input[name=code_' + (++c) + ']').val() + '\n';
                    xmlContent += '</codigo>\n'
                }
            }
            var url = $('#finish').attr('action');
            var topic_name = $('#hiddenTopicName').val();
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: url,
                type: 'POST',
                data: {"xmlContent": xmlContent, "topic_name": topic_name},
                dataType: 'json',
                success: function( _response ){
                    window.location.href = "/admin/topics";
                },
                error: function(xhr, status, error) {
                    alert(error);
                },
            });
            return 0;
        });

    </script>
@endsection





