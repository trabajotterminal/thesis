@php
    $xml                = $xmlFilePath[0];
    //remove public/ by storage/.
    $xml                = substr($xml, strpos($xml, '/') + 1);
    $T = file_get_contents("storage/" . $xml);
    $title_start_position = strpos($T, '<titulo>');
    $title_end_position = strpos($T, '</titulo>');
    $title = substr($T, $title_start_position, $title_end_position - $title_start_position);
    $T = substr($T, strpos($T, '</titulo>') + 9);
    $T = str_replace(']]>', '', $T);
    $T = str_replace('<![CDATA[', '', $T);
    $T = str_replace('<subtitulo>', '<h4>', $T);
    $T = str_replace('</subtitulo>', '</h4>', $T);

    $T = str_replace('<parrafo>', '<p>', $T);
    $T = str_replace('</parrafo>', '</p><br>', $T);

    $T = str_replace('<codigo-lenguaje>', '<h5>Lenguaje ', $T);
    $T = str_replace('</codigo-lenguaje>', '</h5>', $T);
    
    $T = str_replace('<codigo-contenido>', '<pre><code>', $T);
    $T = str_replace('</codigo-contenido>', '</pre></code><br><br>', $T);

    $T = str_replace('<referencia>', '', $T);
    $T = str_replace('</referencia>', '', $T);

    $T = str_replace('<encabezado>', '<b><p>', $T);
    $T = str_replace('</encabezado>', '<b></p>', $T);

    $T = str_replace('<link>', '<a href="#" style="color:#235cba;">', $T);
    $T = str_replace('</link>', '</a>', $T);

    $T = str_replace('<teoria>', '', $T);
    $T = str_replace('</teoria>', '', $T);
    $user_id            = session('user_id');
    $user_type       = session('user_type');
@endphp
@extends('layouts.app')
@section('title', 'Revisión')

@section('statics-css')
    @include('layouts/statics-css-1')
    <link rel="stylesheet" href="{{ asset('/css/monokai-sublime.css') }}" />
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
    <section>
        <div class="container" style="min-height: calc(100vh - 163px);">
            <h4 style="margin-top:40px;">Teoría {{$action}} por {{strtolower($creator_username)}} para el tema: {{$topic_name}}</h4>
            <div class="divider-line solid light opacity-7"></div>
            <div class="row sec-moreless-padding slide-controls-color-7">
                <div class="col-xs-12 text-left">
                    <h4 class="uppercase oswald">
                        @php
                            echo $title;
                        @endphp
                    </h4>
                    <div class="divider-line solid light opacity-7"></div>
                    <div class="clearfix"></div>
                    <div class="text-box border padding-1">
                        <div class="feature-box-75" style=" text-align: justify;">
                            @php
                                echo $T;
                            @endphp
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <center>
                        <div class="form-group" style="margin:0px;padding:0px;">
                            <form action="{{url('admin/notification/theory/resolve')}}" id="notificationForm" method="POST">
                                <textarea class="form-control" rows="2" style="width:450px;" name="comment" placeholder="Escribe una retroalimentación para {{$creator_username}}."></textarea>
                                <br>
                                <input type="submit" name="accept" class="btn btn-success" value="Aceptar">
                                <input type="submit" name="decline" class="btn btn-danger" style="margin-left:30px;" value="Rechazar">
                            </form>
                        </div>
                    </center>
                </div>
            </div>
            <br><br>
        </div>
    </section>
@endsection

@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script src="{{ asset('/js/highlight.pack.js')}}"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script src="{{ asset('/js/scripts/functions.js')}}" type="text/javascript"></script>
    <script type="text/javascript" async src="{{ asset('/js/mathjax.js?config=AM_CHTML')}}"></script>
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
            url: '{{URL('admin/notification/theory/resolve')}}',
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

