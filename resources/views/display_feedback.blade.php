<style>
    .card {
        background: #fff;
        border-radius: 2px;
        display: inline-block;
        height: 100%;
        position: relative;
        width: 99%;
        margin: 20px;
        padding:15px;
    }
    .card-1 {
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }

    .card-1:hover {
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    }

    .card-2 {
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    }

    .card-3 {
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
    }

    .card-4 {
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    }

    .card-5 {
        box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);
    }

</style>
<div class="row">
@if(count($questions) == 0)
    <br><br><br><br><br><br><br>
    <div class="col-md-12">
        <center><h1>¡Obtuviste una calificación perfecta!</h1></center>
    </div>
@else
    <div class="col-md-12">
        <center>
            <h1>¡Ups! Al parecer cometiste algunos errores</h1>
            <img src="{{URL::asset('images/oops.png')}}" style="width:200px;height:200px;"/>
        </center>
    </div>
    @foreach($questions as $key => $question)
        <div class="col-md-12 col-centered">
            <div class="card card-1">
                <h4>{{$questions[$key][0]}}</h4>
                <h5>Respuesta correcta:{{$right_answers[$key][0]}}</h5>
                <h5>Retroalimentación:  {{$feedbacks[$key][0]}}</h5>
            </div>
        </div>
    @endforeach
    <div class="col-md-12" style="margin-top:20px;margin-bottom:20px;">
        <center>
            <a href="{{url('questionnaire/'.$topic_name)}}" class="btn btn-success">Volver a intentarlo</a>
        </center>
    </div>
@endif
</div>