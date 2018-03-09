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
    $position = strpos($htmlFile, 'font-awesome.min.css');
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
            $htmlFile = str_replace($stringToRemove, "<link rel='stylesheet' href='".asset('storage/'.$category_name.'/'.$topic_name.'/Simulacion/css/'.$css_file_names[$k])."'  type='text/css' />", $htmlFile);
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
            $htmlFile = str_replace($stringToRemove, "<script src='".asset('storage/'.$category_name.'/'.$topic_name.'/Simulacion/js/'.$js_file_names[$k])."'></script>", $htmlFile);
        }
    }

@endphp


@extends('layouts.app')

@section('title', 'Simulacion')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <div id="content">
        @php
            echo $htmlFile;
        @endphp
        @if($user != null && $user_type == 'alumno')
            <form id="updateGlance" method="POST" action="{{url('/simulation/updateGlance')}}">
                {{(csrf_field())}}
                <input type="hidden" id="user_id" value="{{$user}}" />
                <input type="hidden" id="topic_name" value="{{$topic_name}}" />
                <input type="submit" style="display:none"/>
            </form>
        @endif
    </div>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script>
        $("#updateGlance").submit(function(e){
            e.preventDefault();
            var url = $('#updateGlance').attr('action');
            var user_id = $('#user_id').val();
            var topic_name = $('#topic_name').val();
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                type: "POST",
                url: url,
                data: {"topic_name": topic_name, "user_id": user_id, "type": 'S'},
                error: function(data){
                    console.warn('Error occurred while saving some data to server');
                },
                success: function(){
                    console.warn('Data saved...');
                }
            });
            return false;
        });

        function runAjax(){
            var form = document.getElementById('updateGlance');
            if(form){
                $('#updateGlance').submit();
            }
        }

        $(document).ready(function() {
            setTimeout(function() {
                runAjax();
            }, 10000);
        });
    </script>
@endsection





