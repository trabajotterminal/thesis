@php
    $action         = "";
    $object         = "";
    $action_color   = "";
    $object_color   = "";
    $is_topic       = false;
    $is_category    = false;
    $is_content     = false;
    if($notification -> type == 'A'){
        $action         = "agregó";
        $action_color   = "#28B463";
    }
    if($notification -> type == 'E'){
        $action         = 'realizó una edición sobre';
        $action_color   = "#212F3D";
    }
    if($notification -> type == 'D'){
        $action         = 'solicito la eliminación de';
        $action_color   = '#C0392B';
    }
    if($notification -> category_id){
        $object         = " una categoria.";
        $object_color   = '#8E44AD';
        $is_category    = true;
    }
    if($notification -> topic_id){
        $object         = ' un tema.';
        $object_color   = '#21618C';
        $is_topic       = true;
    }
    if($notification -> reference_id){
        $object         = ' contenido de un tema.';
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
    <section class="sec-padding section-light" style="min-height: calc(100vh - 163px);background-color:white">
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
                    @if($notification -> type == 'E')
                        @if($is_topic)
                            <blockquote style="font-size:30px;">
                                <strike>{{$topic -> approved_name}}</strike>
                            </blockquote>
                            <blockquote style="font-size:50px;">
                                {{$topic -> pending_name}}
                                <cite>Categoria: {{$category_topic}}</cite>
                                @php
                                    $tags = "";
                                    for($i = 0; $i < count($tags_topic); $i++){
                                        $tags.= $tags_topic[$i] -> name;
                                        if($i < count($tags_topic) - 1)
                                            $tags.=', ';
                                        else
                                            $tags.='.';
                                    }
                                @endphp
                                @if(count($tags_topic))
                                    <cite>Tags: {{$tags}}</cite>
                                @else
                                    <cite>Sin tags</cite>
                                @endif
                            </blockquote>
                        @endif
                        @if($is_category)
                            <blockquote style="font-size:30px;">
                                <strike>{{$category -> approved_name}}</strike>
                            </blockquote>
                            <blockquote style="font-size:50px;">
                                {{$category -> pending_name}}
                            </blockquote>

                        @endif
                    @endif
                    @if($notification -> type == 'A')
                        <blockquote>
                            @if($is_topic)
                                {{$topic -> pending_name}}
                                <cite>Categoria: {{$category_topic}}</cite>
                                @php
                                    $tags = "";
                                    for($i = 0; $i < count($tags_topic); $i++){
                                        $tags.= $tags_topic[$i] -> name;
                                        if($i < count($tags_topic) - 1)
                                            $tags.=', ';
                                        else
                                            $tags.='.';
                                    }
                                @endphp
                                @if(count($tags_topic))
                                    <cite>Tags: {{$tags}}</cite>
                                @else
                                    <cite>Sin tags</cite>
                                @endif
                            @endif
                            @if($is_category)
                                {{$category -> pending_name}}
                            @endif
                        </blockquote>
                    @endif
                    @if($notification -> type == 'D')
                        <blockquote>
                            @if($is_topic)
                                {{$topic -> approved_name}}
                            @endif
                            @if($is_category)
                                {{$category -> approved_name}}
                            @endif
                        </blockquote>
                    @endif
                </center>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
               <center>
                   <div class="form-group" style="margin:0px;padding:0px;">
                       <form action="{{url('admin/notification/resolve')}}" id="notificationForm" method="POST">
                           <textarea class="form-control" rows="2" style="width:450px;" name="comment" placeholder="Escribe una retroalimentación para {{$sender -> username}}."></textarea>
                           <br>
                           <input type="submit" name="accept" class="btn btn-success" value="Aceptar">
                           <input type="submit" name="decline" class="btn btn-danger" style="margin-left:30px;" value="Rechazar">
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
        var action  = "";
        var notification_id = <?php echo json_encode($notification -> id) ?>;
        var message = "";
        $("#notificationForm").submit(function(e){
            message = $('textarea[name="comment"]').val();
            e.preventDefault();
            e.stopImmediatePropagation();
            $.ajax ({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                type: 'POST',
                data: {'notification_id': notification_id, 'action': action.name, 'message': message},
                url: '{{URL("/admin/notification/resolve")}}',
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





