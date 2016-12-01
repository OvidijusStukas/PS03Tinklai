<?php

class data_util
{
    public static function gatherDataFromFields($fields) {
        $data = array();

        foreach ($fields as $key => $val) {
            $tmp = null;
            if(is_array($val)) {
                foreach ($val as $key2 => $val2) {
                    $tmp[] = mysql::escape_variable($val2);
                }
            }
            else {
                $tmp = mysql::escape_variable($val);
            }

            $data[$key] = $tmp;
        }

        return $data;
    }
}