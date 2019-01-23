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


namespace App\IndicatorEvents;
use App\Services\Utility;
use \Log;
use \App\Services\CurrencyIndicators;
use \App\IndicatorEvents\EventHelpers;

class IchimokuKinkoHyo {

    public $tenkanConversionLineLength = 9;
    public $kijunBaseLineLength = 29;
    public $senkouLeadingSpanBLength = 52;
    public $chikouLaggingSpanPeriodsPast = 26;

    public $strategyLogger;

    public $line = [];

    public function __construct() {
        $this->utility = new Utility();
        $this->indicators = new CurrencyIndicators();
        $this->eventHelpers = new EventHelpers();
    }

    public function calculateHighLowAverage($rates) {
        $highs = array_map($rates, function($rate) {
            return $rate->highMid;
        });

        $lows = array_map($rates, function($rate) {
            return $rate->lowMid;
        });

        $highPrice = max($highs);
        $lowPrice = min($lows);

        return (($highPrice + $lowPrice)/2);
    }

    public function calculateLines($rates) {
        foreach ($rates as $index=>$rate) {
            if ($index >= $this->senkouLeadingSpanBLength) {
                $relevantRates = $this->utility->getRelevantArraySubsetWhileIterating($rates, $index, $this->senkouLeadingSpanBLength);

                $tenkanConversionRates = $this->utility->getLastXElementsInArray($relevantRates, $this->tenkanConversionLineLength);
                $tenkanConversionValue = $this->calculateHighLowAverage($tenkanConversionRates);

                $kijunBaseRates = $this->utility->getLastXElementsInArray($relevantRates, $this->kijunBaseLineLength);
                $kijunBaseValue = $this->calculateHighLowAverage($kijunBaseRates);

                $senkouLeadingSpanAValue = (($tenkanConversionValue + $kijunBaseValue)/2);

                $senkouLeadingSpanBRates = $this->utility->getLastXElementsInArray($relevantRates, $this->senkouLeadingSpanBLength);
                $senkouLeadingSpanBValue = $this->calculateHighLowAverage($senkouLeadingSpanBRates);

                $chikouLaggingSpanValue = $this->utility->getArrayValueXPeriodsAgo($rates, $index, $this->chikouLaggingSpanPeriodsPast);

                $this->line[] = [
                    'tenkanConversion'=> $tenkanConversionValue,
                    'kijunBase'=> $kijunBaseValue,
                    'senkouLeadingSpanA'=> $senkouLeadingSpanAValue,
                    'senkouLeadingSpanB'=> $senkouLeadingSpanBValue,
                    'chikouLaggingSpan'=> $chikouLaggingSpanValue,
                ];
            }
        }
    }

    public function leadingSpanAAboveSpanB($rates) {
        $this->calculateLines($rates);

        $lastLineValues = end($this->line);

        if ($lastLineValues['senkouLeadingSpanA'] > $lastLineValues['senkouLeadingSpanB']) {
            return 'above';
        }
        elseif ($lastLineValues['senkouLeadingSpanA'] < $lastLineValues['senkouLeadingSpanB']) {
            return 'below';
        }
    }

    public function priceCrossesConversionLine($rates) {
        $this->calculateLines($rates);

        $lastLineValues = end($this->line);

        if ($lastLineValues['senkouLeadingSpanA'] > $lastLineValues['senkouLeadingSpanB']) {
            return 'above';
        }
        elseif ($lastLineValues['senkouLeadingSpanA'] < $lastLineValues['senkouLeadingSpanB']) {
            return 'below';
        }
    }
}