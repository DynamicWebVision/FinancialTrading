<?php namespace App\Services;

use \App\Model\StrategyLog;
use \App\Model\StrategyLogIndicators;
use \App\Model\StrategyLogMessage;
use \App\Model\StrategyLogRates;
use \App\Services\Utility;
use \App\Model\StrategyLogApi;
use Twilio;

class TextMessage  {

    public function sendTextMessage($text) {
        Twilio::message('2817967601', $text);
    }
}