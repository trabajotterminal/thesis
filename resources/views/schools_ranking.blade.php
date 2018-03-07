@php
    /*for($i = 0; $i < count($schools); $i++){
        echo $schools[$i] -> PuntajeFinal.'<br>';
    }*/
@endphp
<table id="table_schools" class="table table-striped" cellspacing="0" width="100%" height="100%">
    <thead>
    <tr>
        <th>Posición</th>
        <th>Escuela</th>
        <th>Puntuación</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>ESCOM</td>
        <td>10</td>
    </tr>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#table_schools').dynatable();
    });
</script>