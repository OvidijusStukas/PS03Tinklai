<?php

class plan_restriction
{
    public function __construct()
    {
    }

    public function getByPlanId($planId)
    {
        $query =
            "SELECT *
             FROM `plan_restrictions`
             WHERE `planId` = '{$planId}'";

        return mysql::select($query);
    }

    public function insert($data) {
        $query =
            "INSERT INTO `plan_restrictions` (planId, price, mbCount)
             VALUES 
             (
              '${data['planId']}',
              '${data['price']}',
              '${data['mbCount']}'
             )";

        mysql::query($query);

        return mysql::select("SELECT planRestrictionId FROM `plan_restrictions` ORDER BY `planRestrictionId` DESC LIMIT 1")[0]['planRestrictionId'];
    }

    public function update($data) {
        $query =
            "UPDATE `plan_restrictions`
             SET
              `price` = '${data['price']}',
              `mbCount` = '${data['mbCount']}'
             WHERE `planRestrictionId` = '${data['planRestrictionId']}'";

        mysql::query($query);
        return $data['planRestrictionId'];
    }

    public function delete($id) {
        $query =
            "DELETE FROM `plan_restrictions`
             WHERE `planRestrictionId` = ${id}";

        mysql::query($query);
    }
}