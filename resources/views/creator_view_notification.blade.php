@php
    $action         = "";
    $object         = "";
    $action_color   = "";
    $object_color   = "";
    $is_topic       = false;
    $is_category    = false;
    $is_content     = false;

    if($notification -> type == 'MP'){
        $action_color   = "#28B463";
        $action = 'aprobó';
    }

    if($notification -> type == 'MN'){
        $action_color   = "red";
        $action = 'declinó';
    }

    if($notification -> additional_params == 'A'){
        $action.= " la creación";
    }
    if($notification -> additional_params == 'E'){
        $action.= " la edición";
    }
    if($notification -> additional_params == 'D'){
        $action.= " la eliminación";
    }
    if($notification -> category_id){
        $object         = " de la categoria ".$category_name;
        $object_color   = '#8E44AD';
        $is_category    = true;
    }
    if($notification -> topic_id){
        $object         = ' del tema '.$topic_name;
        $object_color   = '#21618C';
        $is_topic       = true;
    }
    $type = "";
    if($notification -> reference_id){
        if($reference_type == 'T'){
            $type = 'de la teoria';
        }
        if($reference_type == 'S'){
            $type = 'de la simulación';
        }
        if($reference_type == 'C'){
            $type = 'del cuestionario';
        }
        $object         = ' '.$type.' del tema '.$topic_name;
        $object_color   = '#3498DB';
        $is_content     = true;
    }
@endphp
@extends('layouts.app')
@section('title', 'Notificación')
@section('statics-css')
    @include('layouts/statics-css-1')
    <style>
        body{
            overflow-x: hidden;
        }
        blockquote {
            font-family: Georgia, serif;
            font-size: 48px;
            font-style: italic;
            width: 700px;
            margin: 0.25em 0;
            padding: 0.35em 40px;
            line-height: 1.45;
            position: relative;
            color: #383838;
        }

        blockquote:before {
            display: block;
            padding-left: 10px;
            content: "\201C";
            font-size: 80px;
            position: absolute;
            left: -20px;
            top: -20px;
            color: #7a7a7a;
        }

        blockquote cite {
            color: #999999;
            font-size: 14px;
            display: block;
            margin-top: 5px;
        }

        blockquote cite:before {
            content: "\2014 \2009";
        }
    </style>
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <section class="sec-padding section-light" style="min-height:550px;background-color:white">
        <div class="row">
            <div class="col col-centered text-center">
                <h1>
                    <span>{{ucwords($sender -> username)}} </span>
                    <span  style="color:{{$action_color}}">{{$action}}</span>
                    <span style="color:{{$object_color}}">{{$object}}</span>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <br><br>
                <center>
                    @if($notification -> message)
                        <blockquote style="font-size:30px;">
                            {{$notification -> message}}
                        </blockquote>
                        <center><img src="{{ URL::asset('/images/thumbs.png')}}" style="width:50px;height:50px;margin-top:10px;"/></center>
                    @else
                        <center><img src="{{ URL::asset('/images/thumbs.png')}}" style="width:150px;height:150px;margin-top:10px;"/></center>
                    @endif
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
@endsection





