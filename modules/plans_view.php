<?php
    include 'models/plan_model.php';
    $plan = new plan_model();

    include 'models/user_model.php';
    $user = new user_model();

    $planFields = $plan->get($item_id);
    $hasPlan = false;
    if ($userRole == '3')
    {
        $hasPlan = $user->checkIfAlreadyHasPlan($_SESSION['user']['userId']);
    }
?>

<div>
    <h2>Planas: <?php echo $planFields['name']; ?></h2>
    <p><?php echo $planFields['description']; ?></p>

    <?php
        if ($hasPlan)
            echo "<a class='btn btn-success pull-right' href='index.php?module=my_plan&id={$item_id}&action=new' type='button'>Užsakyti</a>"
    ?>
    <a class="btn btn-warning" href="index.php?module=<?php echo $module; ?>" type="button">Gryšti</a>
</div>
