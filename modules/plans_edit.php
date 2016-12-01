<?php
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['userId']) || $_SESSION['user']['userRoleId'] != '1')
        header("Location: index.php");

    include "models/plan_model.php";
    $plan = new plan_model();

    include "models/plan_restriction_model.php";
    $plan_restriction = new plan_restriction();

    $plan_fields = array();
    $plan_restrictions = array();

    if (!empty($_POST['submit']))
    {
        include 'utils/data_util.php';
        $data = data_util::gatherDataFromFields($_POST);
        $data['planId'] = empty($_POST['planId']) ? $plan->insert($data) : $plan->update($data);

        if (!empty($data['planRestrictionId'])) {
            for ($i = 0; $i < count($data['planRestrictionId']); $i++) {
                if (empty($data['price'][$i]) || empty($data['mbCount'][$i]))
                    continue;

                $restrictionData = array();

                $restrictionData['planId'] = $data['planId'];
                $restrictionData['price'] = $data['price'][$i];
                $restrictionData['mbCount'] = $data['mbCount'][$i];
                $restrictionData['planRestrictionId'] = $data['planRestrictionId'][$i];

                $restrictionData['planRestrictionId'] == -1 ? $plan_restriction->insert($restrictionData) : $plan_restriction->update($restrictionData);
            }
        }
        if (!empty($data['plan_restriction_removed'])) {
            foreach ($data['plan_restriction_removed'] as $key => $pr) {
                $plan_restriction->delete($pr);
            }
        }

        header("Location: index.php?module={$module}");
    }
    elseif (!empty($item_id))
    {
        $plan_fields = $plan->get($item_id);
        $plan_restrictions = $plan_restriction->getByPlanId($item_id);
    }
?>
<h2>Naujas planas</h2>
<form id="form" method="POST">
    <div class="list-container">
            <div class="form-group">
                <label>Pavadinimas</label>
                <input class="form-control" name="name" type="text" placeholder="Pavadinimas" value="<?php echo isset($plan_fields['name']) ? $plan_fields['name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Fiksuota kaina</label>
                <input class="form-control" name="fixedPrice" type="text" placeholder="Fiksuota kaina" value="<?php echo isset($plan_fields['fixedPrice']) ? $plan_fields['fixedPrice'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>MB kiekis</label>
                <input class="form-control" name="fixedCount" type="text" placeholder="MB kiekis" value="<?php echo isset($plan_fields['fixedCount']) ? $plan_fields['fixedCount'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Aprašymas</label>
                <textarea style="resize: vertical;" class="form-control" name="description" placeholder="Aprašymas"><?php echo isset($plan_fields['description']) ? $plan_fields['description'] : ''; ?></textarea>
            </div>
    </div>
    <h2>Apribojimai</h2>
    <div class="list-container">
        <?php
            foreach ($plan_restrictions as $key => $pr)
            {
                $planId = $pr['planRestrictionId'];
                echo "
                <div data-target='restriction'>
                     <div class='form-group'>
                        <label>Kaina</label>
                        <input class='form-control' name='price[]' type='text' placeholder='Fiksuota kaina' value='{$pr['price']}'>                        
                        <span class='glyphicon glyphicon-remove remove-icon' onclick='removePlanRestrictionById({$planId});'></span>
                    </div>
                    <div class='form-group'>
                        <label>MB viršytas kiekis</label>
                        <input class='form-control' name='mbCount[]' type='text' placeholder='MB kiekis' value='{$pr['mbCount']}'>
                    </div>
                    <input type='hidden' name='planRestrictionId[]' value='{$planId}'>
                    </br>
                </div>
                ";
            }
        ?>
    </div>

    <input type="hidden" name="planId" value="<?php echo isset($plan_fields['planId']) ? $plan_fields['planId'] : ''; ?>">
    <button class="btn btn-warning" type="button" onclick="addPlanRestriction()"><span class="glyphicon glyphicon-plus"></span> Pridėti naują</button>
    <input class="btn btn-success pull-right" type="submit" name="submit" value="Išsaugoti">
</form>

