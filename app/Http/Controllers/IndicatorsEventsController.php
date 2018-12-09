<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\IndicatorEvent;
use \App\Model\IndicatorEventTypes;


class IndicatorsEventsController extends Controller {

    public $logQuery;
    public $orderDesc = true;

    public $pageCount = 10;

    public function index() {
        return IndicatorEvent::all();
    }

    public function store() {
        $post = Request::all();
        $newIndicatorEvent = new IndicatorEvent();

        $newIndicatorEvent->indicator_id = $post['indicator_id'];
        $newIndicatorEvent->name = $post['name'];
        $newIndicatorEvent->description = $post['description'];
        $newIndicatorEvent->event_type = $post['event_type'];
        $newIndicatorEvent->variable_declarations = $post['variable_declarations'];
        $newIndicatorEvent->method_call = $post['method_call'];

        if (isset($post['opposing_condition_a'])) {
            $newIndicatorEvent->opposing_condition_a = $post['opposing_condition_a'];
        }

        if (isset($post['opposing_condition_b'])) {
            $newIndicatorEvent->opposing_condition_b = $post['opposing_condition_b'];
        }

        $newIndicatorEvent->save();

        return $newIndicatorEvent;
    }

    public function getIndicatorEventTypes() {
        return IndicatorEventTypes::all()->toArray();
    }

    public function allIndicatorEventsForIndicator($indicatorId) {
        return IndicatorEvent::where('indicator_id', '=', $indicatorId)->get()->toArray();
    }
}