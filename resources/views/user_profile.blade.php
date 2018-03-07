@extends('layouts.app')

@section('title', 'Inicio')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')

@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
@endsection





