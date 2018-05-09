@php
    $tags_array = [];
    for($i = 0; $i < count($tags); $i++){
        $tags_array[$i] = $tags[$i] -> name;
    }
@endphp
@extends('layouts.app')
@section('title', 'Temas')
@section('statics-css')
   @include('layouts/statics-css-1')
    <link rel="stylesheet" href="{{ URL::asset('/css/jquery.tagit.css')}}"  type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('/css/tagit.ui-zendesk.css')}}"  type="text/css" />
    <script src="{{URL::asset('/js/jquery_1.10.2.min.js')}}" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::asset('/js/jquery-ui_1.9.2.min.js')}}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ URL::asset('/js/tag-it.js')}}"></script>
    <style>
        ::placeholder {
            color: white;
            opacity: 1;
        }
    </style>
    <script>
        $(function(){
            var sampleTags = <?php echo json_encode($tags_array); ?>;
            $('#allowSpacesTags').tagit({
                availableTags: sampleTags,
                allowSpaces: true,
                removeConfirmation: true,
            });
        });
    </script>
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <h1 style="margin-left:20px;margin-top:20px;">Mis temas</h1>
    <section>
        <div class="container" id="topic_list" style="min-height: calc(100vh - 163px);"></div>
    </section>
    <section class="sec-padding section-dark">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                    <h1 class="dosis text-white lspace-sm">Agregar un nuevo tema</h1>
                    <p class="sub-title text-white"></p>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="input_holder">
                        <div class="row">
                            <form id="addTopic" action="{{url('creator/topics/register')}}">
                                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                                    <input  class="email_input" type="search" name="topic_name" style="width:100%;" placeholder="Ingresa el nombre del tema">
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                    <select  id="topicList" style="margin-right:20px;width:100%;"  class="email_input margin-left-3">
                                        <option selected>Selecciona la categoria</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category}}">{{$category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                    <input  value="Agregar" class="email_submit btn btn-primary" style="width:80%;float:right; word-wrap: break-word;font-size:10px;"  type="submit">
                                </div>
                            </form>
                            <br><br><br><br>
                        </div>
                        <form>
                            <p style="color:white;">Tags:</p>
                            <ul id="allowSpacesTags">
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-danger print-error-msg col-sm-5 margin-left-7" style="display:none">
            <ul></ul>
        </div>
    </section>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    <script>
        $(document).ready(function(){
            $('#topic_list').load('{{URL('/creator/topics/list')}}',function(){}).hide().fadeIn();
        });
        $("#addTopic").submit(function(e){
            e.preventDefault();
            var tags = $('#allowSpacesTags').tagit('assignedTags');
            var category_name = $('#topicList').find(":selected").text();
            var topic_name = $("input[name='topic_name']").val();
            var url = $('#addTopic').attr('action');
            $(".print-error-msg").css('display','none');
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: '{{URL('creator/topics/register')}}',
                type: 'POST',
                data: {"category_name" : category_name, "topic_name": topic_name, "tags": tags},
                dataType: 'json',
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        $("#topic_list").fadeOut(300).load("{{URL('/creator/topics/list')}}", function(response, status, xhr) {
                            $(this).fadeIn(500);
                        });
                        $("input[name='topic_name']").val('');
                        document.getElementById('topicList').getElementsByTagName('option')[0].selected = 'selected';
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    }else{
                        printErrorMsg(data.error);
                    }
                },
            });
            function printErrorMsg (msg) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display','block');
                $.each( msg, function( key, value ) {
                    $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                });
            }
            return false;
        });
    </script>
@endsection





