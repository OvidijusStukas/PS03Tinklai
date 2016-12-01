<?php

    if ($action == 'start') {
        include 'models/accounting_period_model.php';
        $period = new accounting_period();
        $period->startPeriod();
        header("Location: index.php?module=accounting");
    }

    if ($userRole != '1' && $userRole != '2')
        header("Location: index.php");

    include 'models/accounting_record_model.php';
    $record = new accounting_record();

    if (!empty($_POST['submit'])) {
        include 'utils/data_util.php';
        $data = data_util::gatherDataFromFields($_POST);

        $valid = $record->updateUsage($data['recordId'], $data['mbUsed']);
        if ($valid)
            header("Location: index.php?module={$module}");
        else
            echo "<script type='text/javascript'>alert('Negalima mažinti išnaudoto kiekio.')</script>";
    }

    $record_fields = $record->get($item_id);
?>

<h2>Vartotojas: <?php echo $record_fields['username']; ?>, planas: <?php echo $record_fields['name']; ?></h2>

<form id="form" method="POST">
    <div class="list-container">
        <div class="form-group">
            <label>Išnaudotas MB kiekis</label>
            <input class="form-control" name="mbUsed" type="text" value="<?php echo isset($record_fields['mbUsed']) ? $record_fields['mbUsed'] : ''; ?>">
        </div>
        <div class="form-group">
            <label>Sumokėta suma</label>
            <input class="form-control" name="amountDebt" type="text" value="<?php echo isset($record_fields['amountPaid']) ? $record_fields['amountPaid'] : ''; ?>" readonly='readonly'>
        </div>
        <div class="form-group">
            <label>Mokėtina suma</label>
            <input class="form-control" name="amountDebt" type="text" value="<?php echo isset($record_fields['amountDebt']) ? $record_fields['amountDebt'] : ''; ?>" readonly='readonly'>
        </div>
    </div>

    <input name="recordId" value="<?php echo isset($record_fields['recordId']) ? $record_fields['recordId'] : ''; ?>" hidden>
    <input class="btn btn-success" type="submit" name="submit" value="Atnaujinti">
</form>