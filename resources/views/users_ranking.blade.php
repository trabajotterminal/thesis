@php
    $users_table    = [];
    $ranking_table  = [];
    $index          = 0;
    for($i = 0; $i < count($ranked_users); $i++){
        $users_table[$index] = $ranked_users[$i] -> username;
        $ranking_table[$index++] = $ranked_users[$i] -> points;
    }
    for($i = 0; $i < count($non_ranked_users); $i++){
        $users_table[$index] = $non_ranked_users[$i] -> username;
        $ranking_table[$index++] = 'Sin registros';
    }
@endphp
<style>
    .gray-color{
        background:  #4790d5  !important;
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
            <td><h4>{{$key + 1}}</h4></td>
            <td>{{$users_table[$key]}}</td>
            <td>{{$ranking_table[$key]}}</td>
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