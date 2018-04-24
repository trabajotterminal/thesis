@if($names)
    @foreach($names as $key => $name)
        <section>
            <div class="pagenation-holder">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <form action ="{{url('creator/categories/edit')}}" method="post" class="editText">
                                <h3 class="editable-text" data-id="{{$name}}">{{$name}}</h3>
                                <input type="submit" style="position: absolute; left: -9999px"/>
                            </form>
                            <div id="error_{{($key + 1)}}" style="display:none;color:red;"><ul></ul></div>
                        </div>
                        <div class="col-md-6" >
                            @if(!$is_approval_pending[$key])
                                <div class="input-group" style="float:right;">
                                    <input name="submit" class="editButton btn btn-primary" value="Editar" type="submit" data-id="{{$name}}" data-key="{{($key + 1)}}">
                                    @if($needs_revision[$key])
                                        <span class="input-group-btn"></span>
                                        <form action="{{ url('creator/categories/submitReview') }}" method="POST" class="reviewCategory">
                                            {{(csrf_field())}}
                                            <input type="hidden" name="reviewElementName" value="{{$name}}" >
                                            <input name="submit" value="Enviar revisión" class="btn btn-warning" type="submit" data-id="{{$name}}">
                                        </form>
                                    @endif
                                    @if(!$has_been_approved_once[$key])
                                        <span class="input-group-btn"></span>
                                        <form action="{{ url('creator/categories/delete') }}" method="POST" class="deleteCategory">
                                            {{(csrf_field())}}
                                            <input type="hidden" name="deletedElementName" value="{{$name}}" >
                                            <input name="submit" value="Eliminar" class="btn btn-danger" type="submit" data-id="{{$name}}">
                                        </form>
                                    @endif
                                </div>
                            @else
                                <span style="float:right;"><u>La categoría está en proceso de aprobación.</u></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach
@else
    <section>
        <div class="container">
            <div class="row text-center" style="margin-top:150px;">
                <h1 style="font-size:60px;font-weight: 100">No has creado ninguna categoria.</h1>
            </div>
        </div>
    </section>
@endif

<script>
    var buttonpressed = 0;
    var textPressed = "";
    var keyPressed = 0;
    $('.btn-danger').click(function() {
        buttonpressed = $(this).attr('data-id');
    });

    $('.btn-warning').click(function() {
        buttonpressed = $(this).attr('data-id');
    });

    $('.editButton').click(function() {
        $(this).replaceWith('<input name="submit" class="saveButton btn btn-success" style="width:112px;" value="Guardar" type="submit">');
        $('.btn-danger').prop("disabled", true);
        $('.editButton').prop("disabled", true);
        $('.btn-warning').prop("disabled", true);
        textPressed = $(this).attr('data-id');
        keyPressed  = $(this).attr('data-key');
        var category_name = textPressed;
        var input = $('<input id="editedText" name="editedText" type="text" value="' + category_name + '" />')
        $(".editable-text[data-id='"+category_name+"']").text('').append(input);
        input.select();
        $('.saveButton').click(function() {
            $(".editText").submit();
        });
    });



    $(".deleteCategory").submit(function(e){
        e.preventDefault();
        var category_name = buttonpressed;
        var url = $('.deleteCategory').attr('action');
        $.ajax({
            beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
            url: '{{URL('creator/categories/delete')}}',
            type: 'POST',
            data: {"category_name" : category_name},
            dataType: 'json',
            success: function( _response ){
                $("#category_list").fadeOut(300).load("{{URL('/creator/categories/list')}}", function(response, status, xhr) {
                    $(this).fadeIn(500);
                });
            },
            error: function(xhr, status, error) {
                alert(error);
            },
        });
        return false;
    });

    $(".reviewCategory").submit(function(e){
        e.preventDefault();
        var category_name = buttonpressed;
        var url = $('.reviewCategory').attr('action');
        $.ajax({
            beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
            url: '{{URL('creator/categories/submitReview')}}',
            type: 'POST',
            data: {"category_name" : category_name},
            dataType: 'text',
            success: function( _response ){
                $("#category_list").fadeOut(300).load("{{URL('/creator/categories/list')}}", function(response, status, xhr) {
                    $(this).fadeIn(500);
                });
            },
            error: function(xhr, status, error) {
                alert(error);
            },
        });
        return false;
    });


    $(".editText").submit(function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var url = $('.editText').attr('action');
        var category_name = textPressed;
        var newCategoryName = document.getElementById('editedText').value;
        $.ajax({
            beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
            url: '{{URL('creator/categories/edit')}}',
            type: 'POST',
            data: {"category_name" : category_name, "new_category_name": newCategoryName},
            dataType: 'json',
            success: function(data) {
                if($.isEmptyObject(data.error)){
                    $("#category_list").fadeOut(300).load("{{URL('/creator/categories/list')}}", function(response, status, xhr) {
                        $(this).fadeIn(500);
                    });
                    var text = $('#editedText').val();
                    $('#editedText').parent().text(text);
                    $('#editedText').remove();
                }else{
                    printErrorMsg(data.error, keyPressed);
                }
            },
        });

        function printErrorMsg (msg, id) {
            var div = "#error_"+id;
            $(div).find("ul").html('');
            $(div).css('display','block');
            $.each( msg, function( key, value ) {
                $(div).find("ul").append('<li>'+value+'</li>');
            });
        }
        return false;
    });

</script>