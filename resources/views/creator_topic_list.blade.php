<style>
    .editable-tags ul.tagit input[type="text"] {
        color: black;
        background: none;
    }
</style>
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
                        <form action ="{{url('creator/topics/edit')}}" method="post" class="editTopic">
                            {{(csrf_field())}}
                            <div class="col-md-4">
                                <h3 class="editable-text" data-id="{{$topic}}">{{$topic}}</h3>

                                <a href="{{url('/creator/topic/'.$topic)}}">
                                    <img src="{{ URL::asset('/images/content.png')}}" style="width:35px;height:35px;margin-right: 10px;"/>
                                    Administrar contenido
                                </a>
                            </div>
                            <div class="col-md-4" >
                                <h3 class="editable-select" data-id="{{$topic}}">{{$topics_categories[$topic]}}</h3>
                                <input type="submit" style="position: absolute; left: -9999px"/>
                            </div>
                        </form>
                        <div class="col-md-4" >
                            <div class="input-group" style="float:right;">
                                <input name="submit" value="Editar" class="editButton btn btn-primary" type="submit" data-id="{{$topic}}"  data-key="{{($key + 1)}}">
                                <span class="input-group-btn"></span>
                                <form action="{{ url('creator/topics/delete') }}" method="POST" class="deleteTopic">
                                    {{(csrf_field())}}
                                    <input type="hidden" name="deletedElementName" >
                                    <input name="submit" value="Eliminar" class="btn btn-danger" type="submit" data-id="{{$topic}}">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <span class="editable-tags" data-id="{{$topic}}" data-value="{{$topics_tags[$key]}}" style="color:black;">
                                    @if(count($topics_tags[$key]) > 0)
                                    Tags:
                                @else
                                    El tema no cuenta con tags.
                                @endif
                                @php
                                    for($i = 0; $i < count($topics_tags[$key]); $i++){
                                        echo $topics_tags[$key][$i] -> name;
                                        if($i < count($topics_tags[$key]) - 1)
                                            echo ', ';
                                        else
                                            echo '.';
                                    }
                                @endphp
                                </span>
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
                <h1 style="font-size:60px;font-weight: 100">No has creado ningún tema.</h1>
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
        e.stopImmediatePropagation();
        e.preventDefault();
        topic_name      = document.getElementById('editedText').value;
        category_name   = document.getElementById('editedList').value;
        var url = $('.editTopic').attr('action');
        var tags = $('#tag_input').tagit('assignedTags');
        $.ajax({
            beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
            url: url,
            type: 'POST',
            data: {"topic_name" : textPressed, "new_topic_name": topic_name, "new_category_name": category_name, "tags": tags},
            dataType: 'json',
            success: function(data) {
                if($.isEmptyObject(data.error)){
                    console.warn(data);
                    $("#topic_list").fadeOut(300).load("/creator/topics/list", function(response, status, xhr) {
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
        e.stopImmediatePropagation();
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
                $("#topic_list").fadeOut(300).load("/creator/topics/list", function(response, status, xhr) {
                    $(this).fadeIn(500);
                });
            },
            error: function(xhr, status, error) {

            },
        });
        return false;
    });

    $('.btn-danger').click(function() {
        buttonpressed = $(this).attr('data-id');
    });

    $('.editButton').click(function() {
        $(this).replaceWith('<input name="submit" class="saveButton btn btn-success" style="width:112px;" value="Guardar" type="submit">');
        $('.btn-danger').prop("disabled", true);
        $('.editButton').prop("disabled", true);
        textPressed = $(this).attr('data-id');
        keyPressed  = $(this).attr('data-key');
        topic_name = textPressed;
        var tags_object = $(".editable-tags[data-id='"+topic_name+"']").attr("data-value");
        tags_object = JSON.parse(tags_object);
        var tag_list = "";
        for(i = 0; i < tags_object.length; i++){
            tag_list += "<li>" + tags_object[i].name + "</li>";
        }
        var input = $('<input id="editedText" name="editedText" type="text" value="' + topic_name + '" />');
        var tags_inputs = $('<div class="input_holder"><form><p style="color:black;">Tags:</p><ul id="tag_input" style="color:black">'+tag_list+'</ul></form></div>');
        $(".editable-text[data-id='"+topic_name+"']").text('').append(input);
        $(".editable-tags[data-id='"+topic_name+"']").text('').append(tags_inputs);
        $('#tag_input').tagit({
            availableTags: [],
            allowSpaces: true,
            removeConfirmation: true,
        });
        input.select();
        var select = $('<select id="editedList">');
        var category_name = "";
        select.append('<option selected> Selecciona la categoria</option>');
        $.ajax ({
            beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
            type: 'GET',
            data: {'topic': topic_name},
            url: "/creator/categories/list/json",
            datatype: "json",
            success: function(data) {
                category_name = data.category_name;
                $.each(data.categories, function(index, value){
                    select.append('<option id="' + index + '">' + value.name + '</option>');
                });
                $(".editable-select[data-id='"+ topic_name +"']").text('').append(select);
                $(".editable-select[data-id='"+ topic_name +"'] select").val(category_name);
            }
        });
        select.change(function () {
            var X = $('#editedList').val();
            new_category_name = X;
        });

        $('.saveButton').click(function() {
            $(".editTopic").submit();
        });
    });
</script>