@php
    $schools_table    = [];
    $ranking_table  = [];
    $index          = 0;
    for($i = 0; $i < count($ranked_schools); $i++){
        $schools_table[$index] = $ranked_schools[$i] -> name;
        $ranking_table[$index++] = $ranked_schools[$i] -> points;
    }
    for($i = 0; $i < count($non_ranked_schools); $i++){
        $schools_table[$index] = $non_ranked_schools[$i] -> name;
        $ranking_table[$index++] = 'Sin registros';
    }
@endphp
<table id="table_schools" class="table table-striped" cellspacing="0" width="100%" >
    <thead>
    <tr>
        <th>Posición</th>
        <th>Escuela</th>
        <th>Puntuación</th>
        <th></th>
    </tr>
    </thead>
    <tbody height="300px">
    @foreach($schools_table as $key => $user)
        <tr>
            <td>{{$key + 1}}</td>
            <td>{{$schools_table[$key]}}</td>
            <td>{{$ranking_table[$key]}}</td>
            <td><a href="{{ url('admin/statistics/school/'. $schools_table[$key]) }}"><button type="button" class="btn btn-default btn-xs">Perfil</button></a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#table_schools').dynatable();
    });
</script>