@php
    /*for($i = 0; $i < count($users); $i++){
        echo $users[$i] -> PuntajeFinal.'<br>';
    }*/
@endphp
<table id="table_users" class="table table-striped" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Posición</th>
        <th>Usuario</th>
        <th>Puntuación</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>bolitas</td>
        <td>10</td>
    </tr>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#table_users').dynatable();
    });
</script>