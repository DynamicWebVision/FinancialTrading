<?php namespace App\Services;

class TransactionAmountHelpers  {
    public function kellyCriterion($potentialGain, $potentialLoss, $probabilityOfWinning) {
        $b = $potentialGain/$potentialLoss;
        return ($probabilityOfWinning*($b + 1) - 1)/$b;
    }

    public function perUnitRisk($rate, $pip, $stopLoss) {
        return abs(($rate - ($pip*$stopLoss + ($pip*2))) - $rate);
    }

    public function calculatePositionAmount($rate, $pip, $stopLoss, $riskAmount) {
        $perUnitRisk = $this->perUnitRisk($rate, $pip, $stopLoss);
        return round($riskAmount/$perUnitRisk);
    }
}