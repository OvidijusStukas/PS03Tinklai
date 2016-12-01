<?php

class user_model
{
    public function __construct()
    {
    }

    public function checkIfAlreadyHasPlan($userId) {
        $query =
            "SELECT 1
             FROM `user_users`
             WHERE `userId` = '{$userId}' AND `planId` IS NOT NULL";

        $result = mysql::select($query);
        return empty($result);
    }

    public function get($username, $password)
    {
        $query =
            "SELECT *
             FROM `user_users`
             WHERE `username` = '${username}' AND `password` = '${password}'";

        $result = mysql::select($query);
        return count($result) > 0 ? $result[0] : array();
    }

    public function register($data)
    {
        $query =
            "INSERT INTO `user_users` (userRoleId, username, password, email, planId)
             VALUES
             (
              '3',
              '${data['username']}',
              '${data['password']}',
              '${data['email']}',
              NULL
             )";

        mysql::query($query);
    }
}