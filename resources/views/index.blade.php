@extends('layouts.app')

@section('title', 'Inicio')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'index'])
@endsection
@section('content')
    <section class="parallax-section26">
        <div class="section-overlay bg-opacity-0">
            <div class="container sec-tpadding-3 sec-bpadding-3">
                <div class="row">
                    <div class="col-md-8 col-centered">
                        <h1 class="dosis uppercase less-mar3 text-white textoTop">Aprende un nuevo algoritmo</h1>
                        <div class="title-line-4 white align-center"></div>
                        <div class="clearfix"></div>
                        <!--end title-->
                        <br/>
                        <div class="domain-search-holder">
                            <form method="get" id="domain-search-holder" action="domain-search.html">
                                <div>
                                    <input class="input-text" name="dsearch" id="dsearch" placeholder="Búsca algún algoritmo" type="text" />
                                    <input id="searchsubmit" value="Buscar" style="background-color: black" type="submit" />
                                </div>
                            </form>
                        </div>
                        <center><span class="left-move"><a class="text-white uppercase tag" href="#">Segment T.</a></span>
                            <span class="left-move"><a class="text-white uppercase tag" href="#">Fenwick T.</a></span>
                            <span class="right-move"><a class="text-white uppercase tag" href="#">Búsqueda Binaria</a></span>
                            <span class="right-move"><a class="text-white uppercase tag" href="#">DFS</a></span>
                        </center>
                        <!--<span class="text-white">Plataforma para el aprendizaje de algoritmos</span> </div>-->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
@endsection





