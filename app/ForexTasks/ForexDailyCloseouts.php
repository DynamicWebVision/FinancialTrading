<?php namespace \App\ForexTasks;

use \App\Model\OandaAccounts;
use \App\Model\Exchange;
use \App\ForexStrategy\CommonTasksStrategy;

class ForexDailyCloseouts {

    public $path;

    public function closeAccounts() {
        $oandaAccounts = OandaAccounts::where('daily_closeout', '=', 1)->get()->toArray();
        $exchanges = Exchange::get();

        foreach ($oandaAccounts as $account) {
            foreach ($exchanges as $exchange) {
                $strategy = new CommonTasksStrategy();

                $strategy->accountId = $account['oanda_id'];
                $strategy->exchange = $exchange;

                $strategy->closeIfOpen();
            }
        }
    }
}
