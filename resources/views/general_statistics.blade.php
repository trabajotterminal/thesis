@extends('layouts.app')

@section('title', 'Inicio')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')
    <div id="user_ranking" style="width:500px;height:500px;background-color: #7da8c3;"></div>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script>
        $(document).ready(function(){
            $('#user_ranking').load('/admin/users/ranking',function(){}).hide().fadeIn();
        });
    </script>
@endsection





