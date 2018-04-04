@php
    $user_id = session('user_id');
@endphp

@extends('layouts.app')
@section('title', 'Perfil')
@section('statics-css')
    @include('layouts/statics-css-1')
@endsection

@section('menu')
    @include('layouts/menu', ['page' => 'login'])
@endsection
@section('content')
        <div class="container-fluid" style="min-height:550px;">
            <div class="row margin-top3">
                <div class="col-md-6">
                    <center>
                        <h3>{{$user -> username}}</h3>
                        <img src="{{$user -> profile_picture}}" class="img-circle" id="preview_image" style="width:240px;height:240px;margin-bottom:35px;"/>
                        <br>
                        <label class="btn btn-default btn-file">
                            Cambiar foto de perfil
                            <input type="file" style="display: none;" onchange="encodeImageFileAsURL(this)">
                        </label>
                    </center>
                </div>
                <div class="col-md-6">
                    <h4>Información académica.</h4>
                    <hr>
                    <form action="{{url('user/updateInfo')}}"  method="post" id="update">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="institutions">Institución a la que perteneces</label>
                                    <select class="form-control" id="institutions">
                                        @foreach($institution_list as $institution)
                                            <option>{{$institution -> name}}</option>
                                        @endforeach
                                        <option>Otra</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="new_institution">
                                    <label for="new_institution_name">Nombre de tu institución</label>
                                    <input type="text" class="form-control" id="new_institution_name" placeholder="Nombre de tu institución">
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="clubs">Club al que perteneces</label>
                                    <select class="form-control" id="clubs">
                                    </select>
                                </div>
                                <div class="form-group col-md-6" id="new_club">
                                    <label for="new_club_name">Nombre de tu club</label>
                                    <input type="text" class="form-control" id="new_club_name" placeholder="Nombre de tu club">
                                </div>
                            </div>
                        </div>
                        <h4 style="margin-top:150px;">Cambiar contraseña.</h4>
                        <hr>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="old_password">Contraseña actual</label>
                                <input type="password" class="form-control" id="old_password"  placeholder="Introduce tu contraseña actual">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="new_password"> Nueva contraseña</label>
                                <input type="password" class="form-control" id="new_password" placeholder="Introduce tu nueva contraseña">
                            </div>
                        </div>

                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-danger print-error-msg col-md-6 margin-left-7" style="display:none;width:auto;">
                        <ul></ul>
                    </div>
                    <div class="alert alert-success print-success-msg col-md-6 margin-left-7" style="width:auto;display:none">
                        <ul></ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="hidden" id="user_id" value="{{$user_id}}" />
                    <button type="submit" class="btn btn-success" style="float:right;margin-top:80px;">Guardar cambios</button>
                </div>
                </form>
            </div>
        </div>
@endsection
@section('footer')
    @include('layouts/footer')
@endsection

@section('statics-js')
    @include('layouts/statics-js-1')
    <script>
        var institution     = "";
        var new_institution = "";
        var club            = "";
        var new_club        = "";
        var password        = "";
        var new_password    = "";
        var user_id         = <? echo json_encode($user_id); ?>;
        var picture_info    = <? echo json_encode($user -> profile_picture); ?>;

        function updateSelect(){
            $('#institutions').find('option').remove().end();
            var select = document.getElementById('institutions');
            $.ajax ({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                type: 'GET',
                data: {},
                url: "/user/schools/json",
                datatype: "json",
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        $.each(data.schools, function(index, value){
                            var opt = document.createElement('option');
                            opt.value = value.name;
                            opt.innerHTML = value.name;
                            select.appendChild(opt);
                        });
                        var opt = document.createElement('option');
                        opt.value = 'Otra';
                        opt.innerHTML = 'Otra';
                        select.appendChild(opt);
                    }
                }
            }).done(function(){
                $.ajax ({
                    beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                    type: 'GET',
                    data: {'user_id': user_id},
                    url: "/user/getInfo/json",
                    datatype: "json",
                    success: function(data) {
                        if($.isEmptyObject(data.error)){
                            var user_school  = data.school;
                            var user_group   = data.group;
                            if(user_school) {
                                $('#institutions').val(user_school);
                            }else{
                                $("#institutions").val($("#institutions option:first").val());
                                user_school = $("#institutions option:first").val();
                            }
                            var selected_institution_value = user_school;
                            var select = document.getElementById('clubs');
                            var institutions = 0;
                            $('#clubs').find('option').remove().end();
                            $.ajax ({
                                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                                type: 'GET',
                                data: {'school_name': selected_institution_value},
                                url: "/user/school/clubs/json",
                                datatype: "json",
                                success: function(data) {
                                    if($.isEmptyObject(data.error)){
                                        $.each(data.groups, function(index, value){
                                            institutions = institutions + 1;
                                            var opt = document.createElement('option');
                                            opt.value = value.name;
                                            opt.innerHTML = value.name;
                                            select.appendChild(opt);
                                        });
                                    }
                                }
                            }).done(function() {
                                var x = document.getElementById("clubs");
                                var option = document.createElement("option");
                                option.value = "Otro";
                                option.text = "Otro";
                                x.add(option);
                                if(selected_institution_value == 'Otra'){
                                    $('#new_institution').css('visibility', 'visible');
                                }
                                var selected_club_value = $('#clubs').val();
                                if(selected_club_value == 'Otro'){
                                    $('#new_club').css('visibility', 'visible');
                                }
                                if(user_group) {
                                    $('#clubs').val(user_group);
                                }else{
                                    $("#clubs").val($("#clubs option:first").val());
                                }
                            });
                        }
                    }
                });
            });
        }
        function fillGroupsSelect(name){

        }

        function encodeImageFileAsURL(element) {
            var file = element.files[0];
            var reader = new FileReader();
            reader.onload = function(){
                picture_info = reader.result;
            }
            reader.onloadend = function() {
                document.getElementById("preview_image").src = reader.result;
            }
            reader.readAsDataURL(file);
        }
        $(document).ready(function(){
            updateSelect();
            $('#new_club').css('visibility', 'hidden');
            $('#new_institution').css('visibility', 'hidden');
            $('#new_institution_name').val("");
            $('#new_club_name').val("");
        });

        $('#institutions').change(function(){
            $('#new_institution_name').val("");
            $('#new_club_name').val("");
            $('#new_institution').css('visibility', 'hidden');
            $('#new_club').css('visibility', 'hidden');
            var selected_institution_value = $(this).val();
            var select = document.getElementById('clubs');
            $('#clubs').find('option').remove().end();
            $.ajax ({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                type: 'GET',
                data: {'school_name': selected_institution_value},
                url: "/user/school/clubs/json",
                datatype: "json",
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        $.each(data.groups, function(index, value){
                            var opt = document.createElement('option');
                            opt.value = value.name;
                            opt.innerHTML = value.name;
                            select.appendChild(opt);
                        });
                    }
                }
            }).done(function() {
                var x = document.getElementById("clubs");
                var option = document.createElement("option");
                option.value = "Otro";
                option.text = "Otro";
                x.add(option);
                if(selected_institution_value == 'Otra'){
                    $('#new_institution').css('visibility', 'visible');
                }
                var selected_club_value = $('#clubs').val();
                if(selected_club_value == 'Otro'){
                    $('#new_club').css('visibility', 'visible');
                }
            });
        });

        $('#clubs').change(function(){
            $('#new_institution_name').val("");
            $('#new_club_name').val("");
            $('#new_club').css('visibility', 'hidden');
            var selected_value = $(this).val();
            if(selected_value == 'Otro'){
                $('#new_club').css('visibility', 'visible');
            }
        });

        $('#update').submit(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            var url = $('#update').attr('action');
            institution = $('#institutions').val();
            new_institution = $('#new_institution_name').val();
            club = $('#clubs').val();
            new_club = $('#new_club_name').val();
            password = $('#old_password').val();
            new_password = $('#new_password').val();
            user_id = $('#user_id').val();
            $(".print-error-msg").css('display','none');
            $(".print-success-msg").css('display','none');
            $.ajax({
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
                url: url,
                type: 'POST',
                data: {
                    'institution': institution,
                    'new_institution': new_institution,
                    'club': club, 'new_club': new_club,
                    'password': password,
                    'new_password': new_password,
                    'user_id': user_id,
                    'profile_picture': picture_info,
                },
                dataType: 'json',
                success: function(data) {
                    if($.isEmptyObject(data.error)){
                        printSuccessMsg('Los datos fueron actualizados correctamente.');
                        $('#new_club').css('visibility', 'hidden');
                        $('#new_institution').css('visibility', 'hidden');
                        updateSelect();
                    }else{
                        printErrorMsg(data.error);
                    }
                },
            });
            return 0;
        });

        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each( msg, function( key, value ) {
                $(".print-error-msg").find("ul").append('<li>' + (key + 1) + ') '+ value + '</li>');
            });
        }

        function printSuccessMsg(msg){
            $(".print-success-msg").find("ul").html('');
            $(".print-success-msg").css('display','block');
            $(".print-success-msg").find("ul").append('<li>' + msg + '</li>');
        }
    </script>
@endsection





