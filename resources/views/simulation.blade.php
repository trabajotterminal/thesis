@extends('layouts.app')

@section('title', 'Simulación')

@section('statics-css')
    @include('layouts/statics-css-2')
    @foreach($cssFiles as $cssFile)
        @php
            $cssFile = substr($cssFile, strpos($cssFile, '/') + 1);
        @endphp
        <link rel="stylesheet" href="{{ asset('/storage/'.$cssFile)}}" type="text/css" />
    @endforeach
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <div id="simulator"></div>
@endsection

@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-2')
    @php
        for($i = 0; $i < count($jsFiles); $i++){
            asset('storage/'.$jsFiles[$i]);
        }
    @endphp
@endsection





