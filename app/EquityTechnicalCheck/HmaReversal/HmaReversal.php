<?php namespace App\EquityTechnicalCheck\HmaReversal;

use \Log;
use App\EquityTechnicalCheck\EquityTechnicalCheckBase;
use \App\IndicatorEvents\HullMovingAverage;


class HmaReversal extends EquityTechnicalCheckBase {
    public $hmaLength;
    public $hmaChangeDirPeriods;

    public function longCheck() {
        $hmaEvents = new \App\IndicatorEvents\HullMovingAverage;

        if (sizeof($this->rates['simple']) < 50) {
            $this->logger->logMessage("Not Enough Rates");
            return;
        }

        $this->decisionIndicators['hmaRevAfterXPeriods'] = $hmaEvents->hmaChangeDirectionForFirstTimeInXPeriods($this->rates['simple'], $this->hmaLength, $this->hmaChangeDirPeriods);

        if ($this->decisionIndicators['hmaRevAfterXPeriods'] == 'reversedUp') {
            $this->result = true;
            $this->resultSide = 'long';
        }

        $this->storeResult();
    }
}