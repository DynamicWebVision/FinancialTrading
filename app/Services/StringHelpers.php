<?php namespace App\Services;


class StringHelpers  {
    public function getAllValuesUntilString($string, $character) {
        $arr = explode($character, $string, 2);
        return $arr[0];
    }

    public function stringContainsString($parentString, $childString) {
        if (strpos($parentString, $childString) !== false) {
            return true;
        }
        else {
            return false;
        }
    }
}