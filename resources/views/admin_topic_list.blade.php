@if($topics)
    <section>
        <div class="row">
            <div class="col-md-4">
                <h5 style="margin-left:15px;">Nombre</h5>
            </div>
            <div class="col-md-4">
                <h5>Categoria</h5>
            </div>
        </div>
    </section>
    @foreach($topics as $key => $topic)
        <section>
            <div class="pagenation-holder">
                <div class="container">
                    <div class="row">
                        <form action ="{{url('admin/topics/edit')}}" method="post" class="editTopic">
                            {{(csrf_field())}}
                            <div class="col-md-4">
                                <h3 class="editable-text" data-id="{{$topic}}">{{$topic}}</h3>
                                <a href="{{url('/admin/topic/'.$topic)}}">Administrar contenido</a>
                            </div>
                            <div class="col-md-4" >
                                <h3 class="editable-select" data-id="{{$topic}}">{{$topics_categories[$key]}}</h3>
                                <input type="submit" style="position: absolute; left: -9999px"/>
                            </div>
                        </form>
                        <div class="col-md-4" >
                            <div class="input-group" style="float:right;">
                                <input name="submit" value="Editar" class="btn btn-primary" type="submit" data-id="{{$topic}}"  data-key="{{($key + 1)}}">
                                <span class="input-group-btn"></span>
                                <form action="{{ url('admin/topics/delete') }}" method="POST" class="deleteTopic">
                                    {{(csrf_field())}}
                                    <input type="hidden" name="deletedElementName" >
                                    <input name="submit" value="Eliminar" class="btn btn-danger" type="submit" data-id="{{$topic}}">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="error_{{($key + 1)}}" style="display:none;margin-left:-25px;color:red;"><ul></ul></div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach
@else
    <section>
        <div class="container">
            <div class="row text-center" style="margin-top:150px;">
                <h1 style="font-size:60px;font-weight: 100">Aún no hay temas.</h1>
            </div>
        </div>
    </section>
@endif

<script>
    var buttonpressed   = 0;
    var textPressed     = "";
    var topic_name      = "";
    var category_name   = "";
    var keyPressed = 0;
    $(".editTopic").submit(function(e){
        e.preventDefault();
        topic_name      = document.getElementById('editedText').value;
        category_name   = document.getElementById('editedList').value;
        var url = $('.editTopic').attr('action');
        $.ajax({
            beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
            url: url,
            type: 'POST',
            data: {"topic_name" : textPressed, "new_topic_name": topic_name, "new_category_name": category_name},
            dataType: 'json',
            success: function(data) {
                if($.isEmptyObject(data.error)){
                    $("#topic_list").fadeOut(300).load("/admin/topics/list", function(response, status, xhr) {
                        $(this).fadeIn(500);
                    });
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

    $(".deleteTopic").submit(function(e){
        e.preventDefault();
        var topic_name = buttonpressed;
        var url = $('.deleteTopic').attr('action');
        $.ajax({
            beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
            url: url,
            type: 'POST',
            data: {"topic_name" : topic_name},
            dataType: 'json',
            success: function( _response ){
                $("#topic_list").fadeOut(300).load("/admin/topics/list", function(response, status, xhr) {
                    $(this).fadeIn(500);
                });
            },
            error: function(xhr, status, error) {
                alert(error);
            },
        });
        return false;
    });

    $('.btn-danger').click(function() {
        buttonpressed = $(this).attr('data-id');
    });

    $('.btn-primary').click(function() {
        textPressed = $(this).attr('data-id');
        keyPressed  = $(this).attr('data-key');
        topic_name = textPressed;
        var input = $('<input id="editedText" name="editedText" type="text" value="' + topic_name + '" />');
        $(".editable-text[data-id='"+topic_name+"']").text('').append(input);
        input.select();

        var select = $('<select id="editedList">');
        select.append('<option selected> Selecciona la categoria</option>');
        $.ajax ({
            url: "/admin/categories/list/json",
            datatype: "json",
            success: function(data) {
                $.each(data.categories, function(index, value){
                    select.append('<option id="' + index + '">' + value.name + '</option>');
                });
            }
        });
        $(".editable-select[data-id='"+ topic_name +"']").text('').append(select);

        select.change(function () {
            var X = $('#editedList').val();
            new_category_name = X;
            $(this).submit();
        });

        input.blur(function() {

        });
    });
</script>