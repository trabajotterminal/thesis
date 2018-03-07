@extends('layouts.app')

@section('title', 'Temas')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <h1 style="margin-left:20px;margin-top:20px;">Temas</h1>
    <section>
        <div class="container" id="topic_list" style="min-width: 600px;min-height:470px;"></div>
    </section>
    <section class="sec-padding section-dark">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="dosis text-white lspace-sm">Agregar un nuevo tema</h1>
                    <p class="sub-title text-white"></p>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <div class="input_holder">
                        <form id="addTopic" action="{{url('admin/topics/register')}}">
                            <input style="width:50%;" class="email_input" type="search" name="topic_name" placeholder="Ingresa el nombre del tema">
                            <select id="topicList" style="width:30%; margin-right:20px;" class="email_input margin-left-3">
                                <option selected>Selecciona la categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{$category}}">{{$category}}</option>
                                @endforeach
                            </select>
                            <input style="width:15%;" value="Agregar" class="email_submit" type="submit">
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
    @include('layouts/statics-js-1')
    <script>
        $(document).ready(function(){
            $('#topic_list').load('/admin/topics/list',function(){}).hide().fadeIn();
        });

        $("#addTopic").submit(function(e){
            e.preventDefault();
            var category_name = $('#topicList').find(":selected").text();
            var topic_name = $("input[name='topic_name']").val();
            var url = $('#addTopic').attr('action');
            $(".print-error-msg").css('display','none');
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: url,
                type: 'POST',
                data: {"category_name" : category_name, "topic_name": topic_name},
                dataType: 'json',
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        $("#topic_list").fadeOut(300).load("/admin/topics/list", function(response, status, xhr) {
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





