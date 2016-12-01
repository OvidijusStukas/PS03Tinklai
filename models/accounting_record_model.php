<?php

class accounting_record
{
    public function __construct()
    {
    }

    public function getByPlan($userId)
    {
        $query =
            "SELECT ar.*
             FROM `accounting_records` ar
             JOIN `user_users` uu ON uu.userId = ar.userId
             JOIN `accounting_period` ap ON ap.periodId = ar.periodId
             WHERE uu.userId = '{$userId}' AND ap.isActive = '1'";

        $result = mysql::select($query);

        return count($result) > 0 ? $result[0] : array();
    }

    public function get($id)
    {
        $query =
            "SELECT AR.*, UU.username, PP.name
             FROM `accounting_records` AR
             JOIN `accounting_period` AP ON AP.periodId = AR.periodId
             JOIN `user_users` UU ON UU.userId = AR.userId
             JOIN `plan_plans` PP ON PP.planId = UU.planId
             WHERE AP.isActive = 1 AND AR.recordId = '${id}'";

        return mysql::select($query)[0];
    }

    public function getList()
    {
        $query =
            "SELECT AR.recordId, UU.username, PP.name
             FROM `accounting_records` AR
             JOIN `accounting_period` AP ON AP.periodId = AR.periodId
             JOIN `user_users` UU ON UU.userId = AR.userId
             JOIN `plan_plans` PP ON PP.planId = UU.planId
             WHERE AP.isActive = 1";

        return mysql::select($query);
    }

    public function insert($periodId, $planId, $userId)
    {
        $planQuery =
            "SELECT *
             FROM `plan_plans`
             WHERE `planId` = '${planId}'";

        $plan = mysql::select($planQuery)[0];

        $queryRecord =
            "INSERT INTO `accounting_records` (periodId, userId, mbUsed, amountPaid, amountDebt)
             VALUES
             (
              '${periodId}',
              '${userId}',
              '0',
              '0',
              '${plan['fixedPrice']}'
             )";

        mysql::query($queryRecord);

        $updateUser =
            "UPDATE `user_users` 
             SET `planId` = '${planId}'
             WHERE `userId` = '${userId}'";

        mysql::query($updateUser);

        $_SESSION['user']['planId'] = $planId;
    }

    public function delete($recordId, $userId)
    {
        $query =
            "DELETE FROM `accounting_records`
             WHERE `recordId` = '{$recordId}'";

        mysql::query($query);

        $updateQuery =
            "UPDATE `user_users`
             SET planId = NULL
             WHERE `userId` = '${userId}'";

        mysql::query($updateQuery);

        $_SESSION['user']['planId'] = null;
    }

    public function updatePayment($recordId, $debtAmount) {
        $query =
            "UPDATE `accounting_records`
             SET amountDebt = 0,
             amountPaid = ${debtAmount}
             WHERE `recordId` = '${recordId}'";

        mysql::query($query);
    }

    public function updateUsage($recordId, $used) {
        $getPlan =
            "SELECT PP.*, AR.amountPaid, AR.mbUsed
            FROM `accounting_records` AR
            JOIN `user_users` UU ON UU.userId = AR.userId
            JOIN `plan_plans` PP ON PP.planId = UU.planId
            WHERE AR.recordId = ${recordId}";

        $plan = mysql::select($getPlan)[0];

        if ($plan['mbUsed'] > $used) {
            return false;
        }
        else if ($plan['mbUsed'] == $used)
            return true;

        $getRestrictions =
            "SELECT PR.*
             FROM `plan_restrictions` PR
             WHERE PR.planId = ${plan['planId']}
             ORDER BY PR.mbCount DESC";

        $restrictions = mysql::select($getRestrictions);

        $mbOver = $used - $plan['fixedCount'];
        $mbWithoutRestriction = $used > $plan['fixedCount'] ? $plan['fixedCount'] : $used;
        $price = $mbWithoutRestriction * $plan['fixedPrice'] / $plan['fixedCount'] - $plan['amountPaid'];

        if ($mbOver > 0 && !empty($restrictions)) {
            $lowered = true;
            while ($mbOver > 0 && $lowered) {
                foreach ($restrictions as $key => $restriction) {
                    if ($restriction['mbCount'] > $mbOver) {
                        $lowered = false;
                        continue;
                    }

                    $mbOver -= $restriction['mbCount'];
                    $price += $restriction['price'];
                    $lowered = true;
                }
            }
        }

        $updateRecord =
            "UPDATE accounting_records
             SET 
              amountDebt = ${price},
              mbUsed = ${used}
             WHERE `recordId` = ${recordId}";

        mysql::query($updateRecord);
        return true;
    }
}