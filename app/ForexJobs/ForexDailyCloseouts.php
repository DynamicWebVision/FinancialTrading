<?php
namespace App\ForexJobs;

use \App\Model\OandaAccounts;
use \App\Model\Exchange;
use \App\ForexStrategy\CommonTasksStrategy;
use \App\Services\ProcessLogger;
use \App\Broker\OandaV20;

class ForexDailyCloseouts {

    public $path;
    public $logger;

    public function closeAccounts() {
        $this->logger = new ProcessLogger('d_close_accounts');
        $oandaAccounts = OandaAccounts::where('daily_closeout', '=', 1)->get()->toArray();
        $exchanges = Exchange::get();

        $oandaBroker = new OandaV20();

        foreach ($oandaAccounts as $account) {
            foreach ($exchanges as $exchange) {
                $oandaBroker->accountId = $account['oanda_id'];
                $oandaBroker->exchange = $exchange->exchange;

                $this->logger->logMessage('Close Position Check for account '.$account['account_name'].'-'.$account['oanda_id'].' and exchange '.$exchange->exchange);
                $oandaBroker->closePosition();
            }
        }
    }
}
