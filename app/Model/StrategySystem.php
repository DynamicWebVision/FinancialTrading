<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Indicators;
use App\Model\IndicatorEvent;
use \DB;

class StrategySystem extends Model {

    protected $table = 'strategy_system';

    public function getUniqueIndicatorsUsed() {
        $indicatorEvent = new IndicatorEvent();
    }
}
