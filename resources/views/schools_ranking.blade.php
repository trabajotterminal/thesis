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
<style>
    .red-color{
        background:  #cb4335  !important;
    }
</style>
<table id="table_schools" class="table table-striped" cellspacing="0" width="100%" >
    <thead>
    <tr>
        <th class="red-color">Posición</th>
        <th class="red-color">Escuela</th>
        <th class="red-color">Puntuación</th>
        <th class="red-color"></th>
    </tr>
    </thead>
    <tbody height="300px">
    @foreach($schools_table as $key => $user)
        <tr>
            <td><h4>{{$key + 1}}</h4></td>
            <td>{{$schools_table[$key]}}</td>
            <td>{{$ranking_table[$key]}}</td>
            <td><a href="{{ url('admin/statistics/school/'. $schools_table[$key]) }}"><button type="button" class="btn btn-default btn-xs">Ver institución</button></a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#table_schools').dynatable();
    });
</script>