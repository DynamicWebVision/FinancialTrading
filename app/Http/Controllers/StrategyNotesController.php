<?php namespace App\Http\Controllers;

use \DB;
use \Log;
use Request;

use \App\Model\StrategyNotes;


class StrategyNotesController extends Controller {

    public function store() {
        $post = Request::all();

        $strategyNote = new StrategyNotes();

        $strategyNote->strategy_id = $post['strategy_id'];

        $strategyNote->strategy_system_id = $post['strategy_system_id'];

        $strategyNote->note = $post['note'];
        $strategyNote->title = $post['title'];

        if (isset($post['back_test_group_id'])) {
            $strategyNote->back_test_group_id = $post['back_test_group_id'];
        }

        if (isset($post['back_test_process_id'])) {
            $strategyNote->back_test_process_id = $post['back_test_process_id'];
        }

        if (isset($post['frequency_id'])) {
            $strategyNote->frequency_id = $post['frequency_id'];
        }

        if (isset($post['exchange_id'])) {
            $strategyNote->exchange_id = $post['exchange_id'];
        }

        $strategyNote->bt_feedback = $post['bt_feedback'];
        $strategyNote->live_feedback = $post['live_feedback'];
        $strategyNote->for_future = $post['for_future'];

        $strategyNote->save();

        return $strategyNote;
    }

    public function backTestGroupNotes() {
        $strategyNotes = DB::table('strategy_notes')
            ->leftJoin('strategy_iteration', 'strategy_notes.iteration_id', '=', 'strategy_iteration.iteration_id')
            ->leftJoin('decode_frequency', 'strategy_notes.frequency_id', '=', 'decode_frequency.id')
            ->get(['strategy_notes.*', 'strategy_iteration.name', 'decode_frequency.oanda_code as frequency_code'])->toArray();
        return $strategyNotes;
    }

    public function loadNotes($strategyId) {
        $strategyNotes = DB::table('strategy_notes')
            ->leftJoin('strategy_system', 'strategy_notes.strategy_system_id', '=', 'strategy_system.id')
            ->leftJoin('decode_frequency', 'strategy_notes.frequency_id', '=', 'decode_frequency.id')
            ->where('strategy_notes.strategy_id', '=', $strategyId)
            ->get(['strategy_notes.*', 'strategy_system.name', 'strategy_system.method', 'decode_frequency.oanda_code as frequency_code']);
        return $strategyNotes;
    }
}