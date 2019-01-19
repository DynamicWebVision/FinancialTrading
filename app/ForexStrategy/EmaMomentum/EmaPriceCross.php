<?php
/**
 * Created by PhpStorm.
 * User: diego.rodriguez
 * Date: 8/31/17
 * Time: 5:11 PM
 * Description: This is a basic strategy that uses an EMA break through with a longer (100 EMA to start) and short EMA (50 to start).
 *
 * Decisions:
 * BUY      ---> Short EMA crosses above Long EMA
 * Short    ---> Short EMA crosses below Long EMA
 *
 *During Open Position:
 * -If another breakthrouch occurs we will close the current position and open a new, opposite one
 * -If the linear regression slope of the fast EMA is opposite of the position direction, a tighter stop loss will be added.
 */


namespace App\ForexStrategy\EmaMomentum;

use \Log;
use App\IndicatorEvents\EmaEvents;

class EmaPriceCross extends \App\ForexStrategy\Strategy  {

    public $emaLength;

    public function getEntryDecision() {
        $emaEvents = new EmaEvents();
        $emaEvents->strategyLogger = $this->strategyLogger;

        Log::info($this->runId.': New Position Decision Indicators: '.PHP_EOL.' '.$this->logIndicators());

        $this->decision = $emaEvents->emaSide($this->rates, $this->emaLength);
    }

    public function checkForNewPosition() {
        $this->getEntryDecision();

        if ($this->decision == 'long') {
            $this->newLongOrStayInPosition();
        }
        elseif ($this->decision == 'short') {
            $this->newShortOrStayInPosition();
        }
    }
}
