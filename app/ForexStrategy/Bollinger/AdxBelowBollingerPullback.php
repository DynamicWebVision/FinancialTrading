<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 10/12/2017
 * Time: 5:11 PM
 * Description: This is a strategy that tries to follow the Hull Momentum Average HMA trend.
 *
 * Decisions:
 * BUY      ---> HMA meets positive up slope linear regression requirement
 * Short    ---> HMA meets negative up slope linear regression requirement
 */


namespace App\ForexStrategy\Bollinger;
use \Log;
use Illuminate\Support\Facades\DB;

class AdxBelowBollingerPullback extends \App\ForexStrategy\Strategy  {

    public $hullLength;

    public $hmaLinRegCutoff = 0;

    public $adxPeriodLength = 14;
    public $adxCutOff;
    public $adxCutOffForUpwardSlope;

    public $bollingerLength;
    public $bollingerSdMultiplier;

    //Whether you will enter a new position
    public function decision() {
        Log::info($this->runId.': Decision Indicators '.PHP_EOL.$this->logIndicators());

        //if (!$this->fullPositionInfo['open']) {

        if ($this->currentPriceData->bid <= $this->decisionIndicators['bollingerBands']['low'] && end($this->decisionIndicators['adx']) < $this->adxCutOff) {
            Log::warning($this->runId.': NEW LONG POSITION');
            return 'long';
        }
        elseif ($this->currentPriceData->ask >= $this->decisionIndicators['bollingerBands']['high'] && end($this->decisionIndicators['adx']) < $this->adxCutOff) {
            Log::warning($this->runId.': NEW SHORT POSITION');
            return 'short';
        }
        else {
            Log::warning($this->runId.': NO POSITION DECISION');
            return 'nothing';
        }
    }

    public function checkForNewPosition() {
        $this->ratesPips = $this->getRatesInPips($this->rates);

        $this->decisionIndicators['bollingerBands'] = $this->indicators->bollingerBands($this->rates['simple'], $this->bollingerLength,
                                            $this->bollingerSdMultiplier);

        $this->decisionIndicators['adx'] = $this->indicators->adx($this->rates['full'], $this->adxPeriodLength);

        $this->decision = $this->decision();

        if ($this->decision == 'long') {
            $this->newLongOrAdjustSL();
        }
        elseif ($this->decision == 'short') {
            $this->newShortOrAdjustSL();
        }
        elseif ($this->decision == 'nothing') {
            $this->closePosition();
        }
    }
}