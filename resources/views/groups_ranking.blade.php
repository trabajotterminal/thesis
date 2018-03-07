@php
    /*
    for($i = 0; $i < count($groups); $i++){
        echo $groups[$i] -> PuntajeFinal.'<br>';
    }*/
@endphp
<table id="table_groups" class="table table-striped" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Posición</th>
        <th>Grupo</th>
        <th>Puntuación</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>Club de Algoritmia</td>
        <td>10</td>
    </tr>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#table_groups').dynatable();
    });
</script>