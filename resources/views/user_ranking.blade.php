@extends('layouts.app')

@section('title', 'Inicio')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'index'])
@endsection
@section('content')
    <div class="row" style="min-height:550px;">
        <div class="col-md-12">
            <div id="user_ranking" ></div>
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script>
        $(document).ready(function(){
            $('#user_ranking').load('/admin/user/ranking/{{$user_id}}',function(){}).hide().fadeIn();
        });
    </script>
@endsection





