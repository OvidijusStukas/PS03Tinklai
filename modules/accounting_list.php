<?php
    if ($userRole != '1' && $userRole != '2')
        header("Location: index.php");

    include 'models/accounting_record_model.php';
    $record = new accounting_record();

    $record_fields = $record->getList();
?>
<h2>Užsakyti planai</h2>

<div class="row">
    <div class="col-lg-10">
        <input id="search" type="search" class="form-control" placeholder="Planų paieška">
    </div>
    <?php
        if ($userRole == '1')
        {
            echo '<div class="col-lg-2">';
            echo '<a class="btn btn-default pull-right" href="index.php?module=accounting&action=start">Pradeti periodą</a>';
            echo '</div>';
        }
    ?>
</div>

<table id="table" class="table list-container">
    <thead>
    <tr>
        <th>Naudotojo vardas</th>
        <th>Plano pavadinimas</th>
    </tr>
    </thead>
    <tbody>
    <?php
        foreach ($record_fields as $key => $r)
        {
            echo "<tr>";
            echo "<th>${r['username']}</th>";
            echo "<th>${r['name']}<a class='glyphicon glyphicon-pencil black pull-right' href='index.php?module=accounting&id=${r['recordId']}'></a></th>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>

<script>
    var $datatable;
    $(document).ready(function() {
        $datatable = $("#table").DataTable({
            info: false,
            lengthChange: false
        });

        $('#table_filter').remove();

        $('#search').keyup(function () {
            console.log($datatable);
            $datatable.search($(this).val()).draw();
        });
    });
</script>