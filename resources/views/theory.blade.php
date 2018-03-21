@php
    $P = "";
    $T = "";
    $phi = [];

    function preProcess($P, &$phi){
        $M = strlen($P);
        $len = 0;
        $phi[0] = 0;
        $i = 1;
        while ($i < $M){
            if ($P[$i] == $P[$len]){
                $len++;
                $phi[$i] = $len;
                $i++;
            }
            else{

                if ($len != 0){
                    $len = $phi[$len-1];
                }
                else{
                    $phi[$i] = 0;
                    $i++;
                }
            }
        }
    }

    function KMPSearch($P, $T, &$phi, &$my_array){
        $M = strlen($P);
        $N = strlen($T);
        $j  = 0;
        $i = 0;
        while ($i < $N){
            if ($P[$j] == $T[$i]){
                $j++;
                $i++;
            }
            if ($j == $M){
                array_push($my_array, $i - $j);
                $j = $phi[$j-1];
            }
            else if ($i < $N && $P[$j] != $T[$i]){
                if ($j != 0)
                    $j = $phi[$j - 1];
                else
                    $i = $i + 1;
            }
        }
    }

    $xml                = $xmlFile[0];
    $xml                = substr($xml, strpos($xml, '/') + 1);
    $text = file_get_contents("storage/".$xml);
    $T = $text;
    $text_opening = [];
    $text_closing = [];
    $subtitles_opening = [];
    $subtitles_closing = [];
    $code_opening = [];
    $code_closing = [];
    $phi = [];

    $P = "<texto>";
    $phi = [];
    preProcess($P, $phi);
    KMPSearch($P, $T, $phi, $text_opening);


    $P = "</texto>";
    $phi = [];
    preProcess($P,  $phi);
    KMPSearch($P, $T, $phi, $text_closing);


    $P = "<codigo>";
    $phi = [];
    preProcess($P, $phi);
    KMPSearch($P, $T, $phi,$code_opening);


    $P = "</codigo>";
    $phi = [];
    preProcess($P, $phi);
    KMPSearch($P, $T, $phi, $code_closing);


    $P = "<subtitulo>";
    $phi = [];
    preProcess($P, $phi);
    KMPSearch($P, $T, $phi, $subtitles_opening);

    $P = "</subtitulo>";
    $phi = [];
    preProcess($P, $phi);
    KMPSearch($P, $T, $phi, $subtitles_closing);

    $title_start_position = strpos($T, '<titulo>');
    $title_end_position = strpos($T, '</titulo>');
    $title = substr($T, $title_start_position, $title_end_position - $title_start_position);
    $T = substr($T, strpos($T, '</titulo>') + 9);

    $T = str_replace('<subtitulo>', '<h4>', $T);
    $T = str_replace('</subtitulo>', '</h4>', $T);

    $T = str_replace('<texto>', '<p>', $T);
    $T = str_replace('</texto>', '</p><br>', $T);

    $T = str_replace('<codigo>', '<pre><code>', $T);
    $T = str_replace('</codigo>', '</pre></code><br>', $T);

    $T = str_replace('<teoria>', '', $T);
    $T = str_replace('</teoria>', '', $T);
    $user_id            = session('user_id');
    $user_type       = session('user_type');
@endphp
@extends('layouts.app')

@section('title', 'Simulación')

@section('statics-css')
    @include('layouts/statics-css-1')
    <link rel="stylesheet" href="{{ asset('/css/monokai-sublime.css') }}" />
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <section>
        <div class="container" style="min-height:550px;">
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
        </div>
    </section>

    @if($user_id != null && $user_type == 'student')
        <form id="updateGlance" method="POST" action="{{url('/theory/updateGlance')}}">
            {{(csrf_field())}}
            <input type="hidden" id="user_id" value="{{$user_id}}" />
            <input type="hidden" id="topic_name" value="{{$topic_name}}" />
            <input type="submit" style="display:none"/>
        </form>
    @endif
@endsection

@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script src="{{ asset('/js/highlight.pack.js')}}"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script src="{{ asset('/js/scripts/functions.js')}}" type="text/javascript"></script>
    <script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=AM_CHTML"></script>
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
                data: {"topic_name": topic_name, "user_id": user_id, "type": 'T'},
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
            var hasVerticalScroll = false;
            if ($("body").height() > $(window).height()) {
                hasVerticalScroll = true;
            }
            var reachBottom = false;
            window.onscroll = function(ev) {
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                    reachBottom = true;
                }
            };
            setInterval(function() {
                if(!hasVerticalScroll){
                    runAjax();
                }else{
                    if(reachBottom){
                        runAjax();
                    }else{
                        console.warn('NOPE');
                    }
                }
            }, 10000);
        });
    </script>
@endsection





