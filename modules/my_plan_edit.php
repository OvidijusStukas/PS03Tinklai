<?php
    if ($userRole != '3')
        header("Location: index.php");

    $planId = $_GET['id'];
    $userId = $_SESSION['user']['userId'];

    include 'models/accounting_record_model.php';
    $record = new accounting_record();
    $record_fields = $record->getByPlan($userId);

    if ($action == 'new')
    {
        include 'models/accounting_period_model.php';
        $period = new accounting_period();
        $activePeriod = $period->getActive();
        if (empty($activePeriod))
            header("Location: index.php");

        $record->insert($activePeriod['periodId'], $planId, $userId);
        header("Location: index.php?module=my_plan&id=${planId}");
    }
    else if ($action == 'delete')
    {
        if ($record_fields['amountDebt'] > 0)
            echo '<script>alert("Prieš atsisakant plano privaloma sumokėti skolą")</script>';
        else
        {
            $record->delete($record_fields['recordId'], $userId);
            header("Location: index.php");
        }
    }

    if (isset($_POST['submit']))
    {
        $record->updatePayment($record_fields['recordId'], $_POST['amountDebt']);
        header("Location: index.php");
    }

    include 'models/plan_model.php';
    $plan = new plan_model();

    $plan_fields = $plan->get($planId);
?>
<h2>Planas: <?php echo $plan_fields['name']; ?></h2>

<form id="form" method="POST">
    <div class="list-container">
        <div class="form-group">
            <label>Išnaudotas MB kiekis</label>
            <input class="form-control" name="mbUsed" type="text" value="<?php echo isset($record_fields['mbUsed']) ? $record_fields['mbUsed'] : ''; ?>" readonly="readonly">
        </div>
        <div class="form-group">
            <label>Mokėtina suma</label>
            <input class="form-control" name="amountDebt" type="text" value="<?php echo isset($record_fields['amountDebt']) ? $record_fields['amountDebt'] : ''; ?>" readonly="readonly">
        </div>
    </div>

    <input class="btn btn-success" type="submit" name="submit" value="Mokėti">
    <a class="btn btn-warning pull-right" href="index.php?module=my_plan&id=<?php echo $planId;?>&action=delete">Atsisakyti plano</a>
</form>