<?php namespace App\Services;

class TransactionAmountHelpers  {
    public function kellyCriterion($potentialGain, $potentialLoss, $probabilityOfWinning) {
        if ($potentialLoss == 0) {
            $potentialLoss = 1;
        }
        $b = $potentialGain/abs($potentialLoss);

        if ($b == 0) {
            return 0;
        }

        return ($probabilityOfWinning*($b + 1) - 1)/$b;
    }

    public function perUnitRisk($rate, $pip, $stopLoss) {
        return abs(($rate - ($pip*$stopLoss + ($pip*2))) - $rate);
    }

    public function calculatePositionAmount($rate, $pip, $stopLoss, $riskAmount) {
        $perUnitRisk = $this->perUnitRisk($rate, $pip, $stopLoss);
        return round($riskAmount/$perUnitRisk);
    }

    public function expectedGainFromOneTransactionTenK($percentToRisk, $potentialGain, $potentialLoss, $probabilityOfWinning) {
        if ($percentToRisk <= 0) {
            return 0;
        }
        else {
            $lossInPips = abs($potentialLoss*.0001);

            $amountToRisk = 10000*$percentToRisk;

            $positionAmount = $amountToRisk/$lossInPips;

            $averageGainPips = $probabilityOfWinning*$potentialGain*.0001;
            $averageLossPips = abs((1-$probabilityOfWinning)*$potentialLoss*.0001);

            $expectedGain = $positionAmount*($averageGainPips - $averageLossPips);
            return $expectedGain;
        }
    }
}