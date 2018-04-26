@extends('layouts.app')
@section('title', 'Creadores')
@section('statics-css')
    @include('layouts/statics-css-1')
@endsection
@section('menu')
    @include('layouts/menu', ['page' => 'category'])
@endsection
@section('content')
    <h1 style="margin-left:20px;margin-top:20px;">Creadores de contenido</h1>
    <section>
        <div class="container" id="creator_list" style="min-height:470px;"></div>
    </section>
    <section class="sec-padding section-dark">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="dosis text-white lspace-sm">Crea un nuevo creador de contenido</h1>
                    <p class="sub-title text-white"></p>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <div class="input_holder">
                        <form id="addCreator" action="{{url('admin/creator/register')}}">
                            <input style="width:40%;" class="email_input" type="search" name="user_email" placeholder="Introduce el correo del creador de contenido">
                            <input style="width:40%;" class="email_input" type="search" name="user_name" placeholder="Asígnale un nombre de usuario">
                            <input style="width:150px;" value="Agregar" class="email_submit" type="submit">
                        </form>
                        <br><br><br><br>
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
            $('#creator_list').load('{{URL('/admin/creator/list')}}',function(){}).hide().fadeIn();
        });
        $("#addCreator").submit(function(e){
            e.preventDefault();
            var email = $("input[name='user_email']").val();
            var username = $("input[name='user_name']").val();
            var url = $('#addCreator').attr('action');
            $(".print-error-msg").css('display','none');
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: '{{URL('admin/creator/register')}}',
                type: 'POST',
                data: {"email" : email, "username": username},
                dataType: 'json',
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        $('#creator_list').load('{{URL('/admin/creator/list')}}',function(){}).hide().fadeIn();
                        $("input[name='user_email']").val('');
                        $("input[name='user_name']").val('');
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





