<?php namespace App\Services;


class StringHelpers  {
    public function getAllValuesUntilString($string, $character) {
        $arr = explode($character, $string, 2);
        return $arr[0];
    }
}