<?php

class accounting_period
{
    public function __construct()
    {
    }

    public function getActive() {
        $query =
            "SELECT *
             FROM `accounting_period`
             WHERE isActive = 1";

        $result = mysql::select($query);
        return count($result) > 0 ? $result[0] : array();
    }

    public function startPeriod() {
        $query =
            "SELECT *
             FROM `accounting_period`
             WHERE isActive = 1";

        $period = mysql::select($query);
        $dateTimeNow = date("Y/m/d");
        $dateTimeNextMonth = date("Y/m/d", strtotime("+1 months"));

        if (count($period) == 0)
        {
            $insert =
                "INSERT INTO `accounting_period` (periodFrom, periodTo, isActive)
                 VALUES
                 (
                  '${dateTimeNow}',
                  '${dateTimeNextMonth}',
                  1
                 )";

            mysql::query($insert);
        }
        else
        {
            $update =
                "UPDATE `accounting_period`
                 SET isActive = 0
                 WHERE isActive = 1";

            mysql::query($update);

            $insert =
                "INSERT INTO `accounting_period` (periodFrom, periodTo, isActive)
                 VALUES
                 (
                  '${dateTimeNow}',
                  '${dateTimeNextMonth}',
                  1
                 )";

            mysql::query($insert);
        }
    }
}