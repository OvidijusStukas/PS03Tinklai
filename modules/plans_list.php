<?php
    include 'models/plan_model.php';
    $plan = new plan_model();

    if (!empty($remove_id))
    {
        if ($plan->checkIfUsed($remove_id))
            $plan->delete($remove_id);
        else
            echo "<script type='text/javascript'>alert('Planas turi naudotojų negalima trinti.')</script>";
    }
?>
<h2>Planai</h2>

<div class="row">
    <div class="col-lg-10">
        <input type="text" class="form-control" placeholder="Planų paieška">
    </div>
    <?php
        if ($userRole == '1')
        {
            echo '<div class="col-lg-2">';
            echo "<a class='btn btn-default pull-right' href='index.php?module=$module&action=new'>Sukurti&nbsp;<span class='glyphicon glyphicon-plus'></span></a>";
            echo '</div>';
        }
    ?>

</div>

<div class="list-container">
    <ul class="list-group">
    <?php
        $data = $plan->getList();
        foreach ($data as $key => $val)
        {
            $id = $val['planId'];
            echo "<li class='list-group-item'>";
            echo "<a href='index.php?module=plans&id={$id}&action=view'>{$val['name']}</a>";
            if ($userRole == '1')
            {
                echo "<a class='glyphicon glyphicon-trash black pull-right' href='#' onclick='deleteConfirmDialog(\"{$module}\", \"{$id}\"); return false;'></a>";
                echo "<a class='glyphicon glyphicon-pencil black pull-right' href='index.php?module={$module}&id={$id}'>&nbsp;</a>";
            }
            echo "</li>";
        }
    ?>
    </ul>
</div>