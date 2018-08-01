<?php namespace App\Services;

class PositionHelpers  {

    public $rates;

    public function mostRecentUpSwing($periodsBack, $pip) {
        echo 'asdflkjasdfljk';
    }

    public function mostRecentDownSwing($pip, $currentPrice, $lowPipMin, $highPipMin) {
        $minFound = false;
        $startPosition = sizeof($this->rates) -10;
        $previousMin = false;

        while (!$minFound) {
            $mostRecentTen = array_slice($this->rates, $startPosition, 10);
            $currentMin = min($mostRecentTen);

            if ($previousMin) {
                if ($previousMin <= $currentMin) {
                    $minFound = true;
                }
            }
            $startPosition = $startPosition -10;

            if ($startPosition < 0) {
                $minFound = true;
            }

            $previousMin = $currentMin;
        }

        $pipCount = round(($currentPrice - $previousMin)/$pip);

        if ($pipCount < $lowPipMin) {
            return $lowPipMin;
        }
        elseif ($pipCount > $highPipMin) {
            return $highPipMin;
        }
        else {
            return $pipCount;
        }
    }

    public function closestFiftyOneHundredUp($currentPrice, $pip, $pipCount) {
        $roundValue = $pip*$pipCount;
        return round((round(($currentPrice+$roundValue/2)/$roundValue)*$roundValue - $currentPrice)/$pip);
    }

    public function closestFiftyOneHundredDown($currentPrice, $pip, $pipCount) {
        $roundValue = $pip*$pipCount;
        return round(($currentPrice - round(($currentPrice-$roundValue/2)/$roundValue)*$roundValue)/$pip);
    }
}
