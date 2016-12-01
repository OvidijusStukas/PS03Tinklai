<?php

class plan_model
{
    public function __construct()
    {
    }

    public function checkIfUsed($id)
    {
        $query =
            "SELECT 1
             FROM `user_users`
             WHERE `planId` = '${id}'";

        $result = mysql::select($query);
        return empty($result);
    }

    public function get($id)
    {
        $query =
            "SELECT * 
             FROM `plan_plans`
             WHERE `planId` = '{$id}'";

        return mysql::select($query)[0];
    }

    public function getList()
    {
        $query =
            "SELECT * 
             FROM `plan_plans`";

        return mysql::select($query);
    }

    public function delete($id)
    {
        $query =
            "DELETE FROM `plan_restrictions`
             WHERE `planId` = '${id}'";

        mysql::query($query);

        $query =
            "DELETE FROM `plan_plans`
             WHERE `planId` = '${id}'";

        mysql::query($query);
    }

    public function insert($data)
    {
        $query =
            "INSERT INTO `plan_plans` (name, description, fixedPrice, fixedCount)
             VALUES 
             (
              '${data['name']}',
              '${data['description']}',
              '${data['fixedPrice']}',
              '${data['fixedCount']}'
             )";

        mysql::query($query);
        return mysql::select("SELECT planId FROM `plan_plans` ORDER BY `planId` DESC LIMIT 1")[0]['planId'];
    }

    public function update($data)
    {
        $query =
            "UPDATE `plan_plans`
             SET 
              `name` = '${data['name']}',
              `description` = '${data['description']}',
              `fixedPrice` = '${data['fixedPrice']}',
              `fixedCount` = '${data['fixedCount']}'
             WHERE `planId` = '${data['planId']}'";

        mysql::query($query);
        return $data['planId'];
    }
}