@php
    $users_table        = [];
    $ranking_table      = [];
    $profile_picture    = [];
    $index          = 0;
    for($i = 0; $i < count($ranked_users); $i++){
        $users_table[$index]        = $ranked_users[$i] -> username;
        $profile_picture[$index]    = $ranked_users[$i] -> profile_picture;
        $ranking_table[$index++]    = $ranked_users[$i] -> points;
    }
    for($i = 0; $i < count($non_ranked_users); $i++){
        $users_table[$index] = $non_ranked_users[$i] -> username;
        $profile_picture[$index] = $non_ranked_users[$i] -> profile_picture;
        $ranking_table[$index++] = 'Sin registros';
    }
@endphp
<style>
    .gray-color{
        background:  #4790d5  !important;
    }
    table{
        border-collapse: collapse;
        border-radius: 1em;
        overflow: hidden;
    }
</style>
<table id="table_users" class="table table-striped" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th class="gray-color">Posición</th>
        <th class="gray-color">Usuario</th>
        <th class="gray-color">Puntuación</th>
        <th class="gray-color"></th>
    </tr>
    </thead>
    <tbody height="300px">
    @foreach($users_table as $key => $user)
        <tr>
            <td><h2>{{$key + 1}}</h2></td>
            <td>
                <div class="row">
                    <div class="col-md-2">
                        <img src="{{$profile_picture[$key]}}" style="float:right;width:40px;height:40px;">
                    </div>
                    <div class="col-md-10">
                        <h5 style="margin-right:70px;">{{$users_table[$key]}}</h5>
                    </div>
                </div>
            </td>
            <td><h5>{{$ranking_table[$key]}}</h5></td>
            <td><a href="{{ url('admin/statistics/user/'. $users_table[$key]) }}"><button type="button" class="btn btn-default btn-xs">Ver perfil</button></a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#table_users').dynatable();
    });
</script>