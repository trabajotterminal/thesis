@php
    $cssLinks = "";
    $jsLinks  = "";
    $user            = session('user');
    $user_type       = session('user_type');
@endphp

@foreach($cssFiles as $cssFile)
    @php
        $cssFile = str_replace("public", "storage", $cssFile);
        $cssLinks .= "<link rel='stylesheet' href='".asset($cssFile)."' type='text/css' />\n";
    @endphp
@endforeach
@foreach($jsFiles as $jsFile)
    @php
        $jsFile = str_replace("public", "storage", $jsFile);
        $jsLinks .= "<script src='".asset($jsFile)."'></script>\n";
    @endphp
@endforeach
@php
    $htmlFile = file_get_contents($path."index.html");
    $htmlFile = str_replace('"', "'", $htmlFile);
    $css_file_names = [];
    $js_file_names = [];
    for($i = 0; $i < count($cssFiles); $i++){
        $pos = strrpos($cssFiles[$i], "/");
        $name = substr($cssFiles[$i], $pos + 1);
        $css_file_names[$i] = $name;
    }

    for($i = 0; $i < count($jsFiles); $i++){
        $pos = strrpos($jsFiles[$i], "/");
        $name = substr($jsFiles[$i], $pos + 1);
        $js_file_names[$i] = $name;
    }
    $position = -1;
    $startingPosition   = -1;
    $closingPosition    = -1;
    $stringToRemove     = "";
    for($k = 0; $k < count($css_file_names); $k++){
        $position = strpos($htmlFile, $css_file_names[$k]);
        if($position){
            $closingPosition = -1;
            while(1){
                if($htmlFile[$position] == '/' && $htmlFile[$position + 1] == '>'){
                    $closingPosition = $position + 1;
                    break;
                }
                ++$position;
                if($position == strlen($htmlFile))
                    break;
            }
            $position = $closingPosition;
            $startingPosition = -1;
            while(1){
                if($htmlFile[$position] == '<'){
                    $startingPosition = $position;
                    break;
                }
                --$position;
                if($position < 0){
                    break;
                }
            }
            $stringToRemove = "";
            for($j = $startingPosition; $j <= $closingPosition; $j++){
                $stringToRemove.= $htmlFile[$j];
            }
            $htmlFile = str_replace($stringToRemove, "<link rel='stylesheet' href='".asset('storage/'.$category_name.'/'.$topic_name.'/Simulacion/changes/css/'.$css_file_names[$k])."'  type='text/css' />", $htmlFile);
        }
    }

    for($k = 0; $k < count($js_file_names); $k++){
        $position = strpos($htmlFile, $js_file_names[$k]);
        if($position){
            $closingPosition = -1;
            while(1){
                if($htmlFile[$position] == '>' && $htmlFile[$position + 1] == '<'){
                    $closingPosition = $position + 10;
                    break;
                }
                ++$position;
                if($position == strlen($htmlFile))
                    break;
            }
            $position = $closingPosition - 10;
            $startingPosition = -1;
            while(1){
                if($htmlFile[$position] == '<'){
                    $startingPosition = $position;
                    break;
                }
                --$position;
                if($position < 0){
                    break;
                }
            }
            $stringToRemove = "";
            for($j = $startingPosition; $j <= $closingPosition; $j++){
                $stringToRemove.= $htmlFile[$j];
            }
            $htmlFile = str_replace($stringToRemove, "<script src='".asset('storage/'.$category_name.'/'.$topic_name.'/Simulacion/changes/js/'.$js_file_names[$k])."'></script>", $htmlFile);
        }
    }

@endphp
@extends('layouts.app')
@section('title', 'Revisión.')
@section('statics-css')
    @include('layouts/statics-css-1')
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
        <h4 class="margin-left-5 margin-top3">Simulación {{$action}} por {{strtolower($creator_username)}}  para el tema: {{$topic_name}}</h4>
        <div class="row">
            <div class="col-md-12">
                <div id="content" style="height:700px;">
                    <iframe width="100%" height="100%;" frameborder="0" id="iFrameContent"></iframe>
                </div>
            </div>
        </div>
        <div class="row margin-top3" style="margin-bottom:30px;">
            <div class="col-md-12">
                <center>
                    <div class="form-group" style="margin:0px;padding:0px;">
                        <form action="{{url('admin/notification/simulation/resolve')}}" id="notificationForm" method="POST">
                            <textarea class="form-control" rows="2" style="width:450px;" name="comment" placeholder="Escribe una retroalimentación para {{$creator_username}}."></textarea>
                            <br>
                            <input type="submit" name="accept" class="btn btn-success" value="Aceptar contenido">
                            <input type="submit" name="decline" class="btn btn-danger" style="margin-left:30px;" value="Rechazar contenido">
                        </form>
                    </div>
                </center>
            </div>
        </div>
    </section>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script>
        $(document).ready(function() {
            var iframe = document.getElementById('iFrameContent');
            var htmlFile = <?php echo json_encode($htmlFile); ?>;
            iframe = iframe.contentWindow || ( iframe.contentDocument.document || iframe.contentDocument);
            iframe.document.open();iframe.document.write(htmlFile);iframe.document.close();
        });

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
                url: '{{URL('admin/notification/simulation/resolve')}}',
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





