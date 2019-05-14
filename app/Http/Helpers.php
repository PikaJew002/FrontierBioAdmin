<?php

if(!function_exists('get_empty_value_for_column')) {
    function get_empty_value_for_column($columnObj) {
        if(preg_match("/^varchar/", $columnObj->Type)) {
            return "";
        } elseif(preg_match("/^int/", $columnObj->Type)) {
            return 0;
        } elseif(preg_match("/^double/", $columnObj->Type)) {
            return 0;
        } elseif(preg_match("/^decimal/", $columnObj->Type)) {
            return 0;
        } elseif(preg_match("/^tinyint/", $columnObj->Type)) {
            return 0;
        } elseif(preg_match("/^date/", $columnObj->Type)) {
            return "";
        } elseif(preg_match("/^timestamp/", $columnObj->Type)) {
            return "";
        } else {
            return "";
        }
    }
}

if(!function_exists('get_type_for_column')) {
    function get_type_for_column($columnObj) {
        if(preg_match("/^varchar/", $columnObj->Type)) {
            return "string";
        } elseif(preg_match("/^tinyint/", $columnObj->Type)) {
            return "boolean";
        } else {
            return $columnObj->Type;
        }
    }
}
