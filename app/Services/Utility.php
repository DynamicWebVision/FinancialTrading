<?php namespace App\Services;

class Utility  {
    public function getRecentArrayValues($array, $itemCount) {
        return array_slice($array, sizeof($array) - $itemCount);
    }

    public function getMultipleArraySets($array, $itemCount, $endOffset) {
        //itemCount is the amount of items in each array
        //endOffset is the amount of arrays

        $endOffset = $endOffset-1;
        $responseArray = [];
        while($endOffset >= 0) {
            $currentArray = $array;

            $currentArray = array_slice($currentArray, 0, sizeof($currentArray)-$endOffset);

            $responseArray[] = array_slice($currentArray, sizeof($currentArray) - $itemCount);
            $endOffset--;
        }
        return $responseArray;
    }

    public function getSumOfArrayProperty($array, $property) {
        $sum = 0;
        foreach($array as $element){
                if (isset($element[$property]))
                $sum += $element[$property];
        }
        return $sum;
    }

    public function minAttributeInArrayOfArrays($array, $prop) {
        return min(array_map(function($element) use($prop) {
            return $element[$prop];
        },
            $array));
    }

    public function recursiveClearDirectoryFiles($directory) {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) {
                recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
    }

    public function getEndValuesOfArray($array, $valueCount) {
        $arrayStart = sizeof($array) - $valueCount;
        $array = array_slice($array, $arrayStart);
        return $array;
    }

    public function trimArraysInComplexArray($complexArray, $maxValues = 5) {
        foreach ($complexArray as $index=>$item) {
            if (is_array($item)) {
                $complexArray[$index] = $this->getLastXElementsInArray($item, $maxValues);
                $innerArray = $complexArray[$index];
                foreach($innerArray as $innerIndex=>$possibleArray) {
                    if (is_array($possibleArray)) {
                        $complexArray[$index][$innerIndex] = $this->getLastXElementsInArray($possibleArray, $maxValues);
                    }
                }
            }
        }
        return $complexArray;
    }

    public function returnArrayObjectWithProperty($array, $search_property, $value) {
        foreach ($array as $element) {
            if ($element[$search_property] == $value) {
                return $element;
                break;
            }
        }
    }

    public function getLastXElementsInArray($array, $elementCount) {
        $startIndex = sizeof($array) - $elementCount;

        return array_slice($array, $startIndex, $elementCount);
    }

    public function getFirstXElementsInArray($array, $elementCount) {
        return array_slice($array, 0, $elementCount);
    }

    public function shortenComplexArray($array, $size = 5) {
        $returnArray = [];

        foreach ($array as $index=>$iterationArray) {
            $returnArray[$index] = $this->getLastXElementsInArray($iterationArray, $size);
        }
        return $returnArray;
    }

    public function findArrayIndexWithElementAttribute($array, $elementId, $searchValue) {
        foreach($array as $index => $element) {
            if($element[$elementId] == $searchValue) return $index;
        }
        return FALSE;
    }

    public function arrayAttributeToSimpleArray($array, $attribute) {
        return array_map(function($element) use ($attribute) {
            if (is_numeric($element[$attribute])) {
                return round($element[$attribute]);
            }
            else {
                return $element[$attribute];
            }
        },$array);
    }

    public function waitUntilSeconds($seconds) {
        $currentSeconds = date('s', time());
        $secondDiff = $seconds - $currentSeconds;

        if ($secondDiff <= 0) {
            return true;
        }
        else {
            sleep($secondDiff);
        }
    }

    public function getXFromLastValue($array, $fromLastCount) {
        return $array[count($array)-($fromLastCount + 1)];
    }

    public function hasNegativeCheck($array) {
        return min($array) < 0;
    }

    public function hasPositiveCheck($array) {
        return max($array) > 0;
    }

    public function mysqlDateTime() {
        return date('Y-m-d H:i:s');
    }

    public function checkCrossOverLevel($array, $crossLevel) {
        $secondToLastValue = $this->getXFromLastValue($array, 1);
        $lastValue = end($array);

        if ($secondToLastValue <= $crossLevel && $lastValue > $crossLevel) {
            return 'crossedAbove';
        }
        elseif ($secondToLastValue >= $crossLevel && $lastValue < $crossLevel) {
            return 'crossedBelow';
        }
        else {
            return false;
        }
    }

    public function removeLastValueInArray($array) {
        unset($array[count($array)-1]);
        return $array;
    }

    public function sleepUntilAtLeastFiveSeconds() {
        $currentSeconds = (int) date('s');

        if ($currentSeconds < 5) {
            sleep(5 - $currentSeconds);
            return true;
        }
        return true;
    }

    public function writeToLine($file, $line, $newText) {
        $filename = $file;
        $line_i_am_looking_for = $line - 1;
        $lines = file( $filename , FILE_IGNORE_NEW_LINES );
        $lines[$line_i_am_looking_for] = $newText;
        file_put_contents( $filename , implode( "\n", $lines ) );
    }

    public function getRelevantArraySubsetWhileIterating($array, $currentIndex, $count) {
        return array_slice($array, ($currentIndex + 1) - $count, $count);
    }

    public function getArrayValueXPeriodsAgo($array, $currentIndex, $periodsInPast) {
        $array = array_slice($array, $currentIndex - $periodsInPast, 1);
        return $array[0];
    }

    public function getRatio($var1, $var2, $round = false) {
        if ($var1 == 0) {
            return 0;
        }
        else {
            return round((($var1/($var1 + $var2))*100));
        }
    }

    public function getRatioDecimal($var1, $var2, $round = false) {
        if ($var1 == 0) {
            return 0;
        }
        else {
            return round((($var1/($var1 + $var2))), 2);
        }
    }

    public function sleepXMinutes($minutes) {
        $secondsToSleep = $minutes*60;
        sleep($secondsToSleep);
    }
}