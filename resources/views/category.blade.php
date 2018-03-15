@php
    $i = 0;
    $routeQ  = "";
    $routeT  = "";
    $routeS  = "";
    $colors     = ['#C0392B', '#9B59B6', '#1A5276', '#17A589', '#F1C40F', '#F4F6F7', '#34495E', '#E3F2FD', '#E8F5E9'];
    $textcolors = ['white'  , 'white'  , 'white'  , 'white'  , 'white'  , 'black'  , 'white'  , 'black'  , 'black'  ];
    $user = session('user');
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
@endphp

@extends('layouts.app')

@section('title', 'Categoria')

@section('statics-css')
    @include('layouts/statics-css-2')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <div class="section-overlay bg-opacity-0">
        <div class="container  sec-bpadding-2">
            <br>
            <h3 class="raleway text-black uppercase">{{$category_name}}</h1>
            <br>
            <div class="row-md-5" id="category" style="min-height:390px;">
                    @while($i < count($topics))
                        @php
                            for($j = 0; $j < count($references[$i]); $j++){
                                if($references[$i][$j] ->  type == 'C'){
                                    $routeQ = $references[$i][$j] ->  route;
                                }
                                if($references[$i][$j] ->  type == 'S'){
                                    $routeS = $references[$i][$j] ->  route;
                                }
                                if($references[$i][$j] ->  type == 'T'){
                                    $routeT = $references[$i][$j] ->  route;
                                }
                            }
                            $color = rand(0, sizeof($colors) -1);
                        @endphp

                        @if($i < count($topics))
                            <div class="col-md-4">
                                <div class="feature-box9 text-center bmargin">
                                    <div class="iconbox-xlarge grayoutline2" style="line-height: 200px;vertical-align: middle;width:200px;height:200px;background-color:{{$colors[$color]}}; color: {{$textcolors[$color]}}">
                                        @php
                                            $firstChar = strtr($topics[$i] -> name, $unwanted_array)[0];
                                        @endphp
                                        <span style="font-size:70px;font-weight:100;">{{$firstChar}}</span>
                                    </div>
                                    <br/>

                                    <h3>{{$topics[$i] -> name}}</h3>
                                    <p style="color">
                                        @if(strlen($routeS))
                                            <u><h5><a href={{ url('/simulation', $topics[$i] -> name) }}>Simulación</a></h5></u>
                                        @endif

                                        @if(strlen($routeT))
                                            <u><h5><a href={{ url('/theory', $topics[$i] -> name) }}>Teoría</a></h5></u>
                                        @endif
                                        @if(strlen($routeQ))
                                            @if($user)
                                            <u><h5><a href={{ url('/questionnaire', $topics[$i] -> name) }}>Cuestionario</a></h5></u>
                                            @endif
                                        @endif
                                    </p>
                                </div>

                                @php
                                    $i++
                                @endphp
                            </div>
                        @endif

                        @if(!($i % 3) && $i <= count($topics) - 1)
                            <div class="col-divider-margin-3"></div>
                        @endif

                        <div class="row-divider-margin-3"></div>
                        @php
                            $routeQ = "";
                            $routeT = "";
                            $routeS = "";
                        @endphp
                    @endwhile
                    @if(count($topics) == 0)
                        <h1 class="text-center" style="padding-top: 150px;">Aún no hay temas para esta categoria.</h1>
                    @endif
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-2')
@endsection





