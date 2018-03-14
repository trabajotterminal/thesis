@php
    $groups_table    = [];
    $ranking_table  = [];
    $index          = 0;
    for($i = 0; $i < count($ranked_groups); $i++){
        $groups_table[$index] = $ranked_groups[$i] -> name;
        $ranking_table[$index++] = $ranked_groups[$i] -> points;
    }
    for($i = 0; $i < count($non_ranked_groups); $i++){
        $groups_table[$index] = $non_ranked_groups[$i] -> name;
        $ranking_table[$index++] = 'Sin registros';
    }
@endphp
<table id="table_groups" class="table table-striped" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Posición</th>
        <th>Grupo</th>
        <th>Puntuación</th>
        <th></th>
    </tr>
    </thead>
    <tbody height="300px">
    @foreach($groups_table as $key => $user)
        <tr>
            <td>{{$key + 1}}</td>
            <td>{{$groups_table[$key]}}</td>
            <td>{{$ranking_table[$key]}}</td>
            <td><a href="{{ url('admin/statistics/group/'. $groups_table[$key]) }}"><button type="button" class="btn btn-default btn-xs">Perfil</button></a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#table_groups').dynatable();
    });
</script>