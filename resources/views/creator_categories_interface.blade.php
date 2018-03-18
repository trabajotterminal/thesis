@extends('layouts.app')

@section('title', 'Categorias')

@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <h1 style="margin-left:20px;margin-top:20px;">Categorias</h1>
    <section>
        <div class="container" id="category_list" style="min-width: 600px;min-height:470px;"></div>
    </section>
    <section class="sec-padding section-dark">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="dosis text-white lspace-sm">Agregar nueva categoria.</h1>
                    <p class="sub-title text-white"></p>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <div class="input_holder">
                        <form id="addCategory" action="{{url('creator/categories/register')}}">
                            <input class="email_input" type="search" name="category_name" placeholder="Ingresa el nombre de tu nueva categoria.">
                            <input value="Agregar" class="email_submit" type="submit">
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
            $('#category_list').load('/creator/categories/list',function(){}).hide().fadeIn();
        });

        $("#addCategory").submit(function(e){
            e.preventDefault();
            var category_name = $("input[name='category_name']").val();
            var url = $('#addCategory').attr('action');
            $(".print-error-msg").css('display','none');
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: url,
                type: 'POST',
                data: {"category_name" : category_name},
                dataType: 'json',
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        $("#category_list").fadeOut(300).load("/creator/categories/list", function(response, status, xhr) {
                            $(this).fadeIn(500);
                        });
                        $("input[name='category_name']").val('');
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





