<?php namespace App\Http\Controllers;

use \App\Model\OandaTrades;
use \App\Model\OandaAccounts;
use \DateTime;
use \App\Services\Utility;
use \App\Services\CronJobs;
use \DB;
use \Log;
use Request;
use App\Broker\OandaV20;
Use App\Services\ProcessLogger;

class TransactionController extends Controller {

    public $environment = 'practice';
    public $liveTrading = 0;
    public $logger;

    public function getOandaTransactions($account = false) {
        ini_set('memory_limit', '-1');

        if ($account) {
            $nextAccount = OandaAccounts::where('live_trading', '=', $this->liveTrading)
                ->where('active', '=', 1)
                ->where('id', '=', $account)
                ->orderBy('last_transaction_pull')
                ->take(1)
                ->get();

            $id = $nextAccount[0]->oanda_id;
            $dbAccountId = $nextAccount[0]->id;
            $lastProcessedId = $nextAccount[0]->last_order_id;
        }
        else {
            $nextAccount = OandaAccounts::where('live_trading', '=', $this->liveTrading)
                ->where('active', '=', 1)
                ->orderBy('last_transaction_pull')
                ->take(1)
                ->get();

            $id = $nextAccount[0]->oanda_id;
            $dbAccountId = $nextAccount[0]->id;
            $lastProcessedId = $nextAccount[0]->last_order_id;
        }
        $this->logger->logMessage('Getting Transactions for Account '.$nextAccount[0]->account_name.' '.$id);

        $broker = new OandaV20($this->environment);

        $broker->accountId = $id;

        if ($lastProcessedId != 0) {
            $transactions = $broker->getTransactionsSince($lastProcessedId);
        }
        else {
            $transactions = $broker->getTransactionHistory();
        }

//        Log::info('Get Transactions Response Account '.$id.' Oanda Response - '.$runId.PHP_EOL);

        if (!isset($transactions)) {
            $this->logger->logMessage('Transactions variable not set');
            return;
        }

        $this->logger->logMessage('Response of '.sizeof($transactions).' transactions.');

      //  $transactions = array_reverse($oanadaResponse->transactions);
        if (sizeof($transactions) > 0) {
            foreach ($transactions as $t) {
                if ($t->type == "ORDER_FILL") {

                    if ($t->reason == 'MARKET_ORDER' || $t->reason == 'LIMIT_ORDER' || $t->reason == 'MARKET_IF_TOUCHED_ORDER') {
                        $oandaTrade = OandaTrades::firstOrNew(['oanda_open_id'=>$t->id]);

                        $oandaTrade->oanda_account_id = $broker->accountId;
                        $oandaTrade->oanda_open_id = $t->id;
                        $oandaTrade->instrument = $t->instrument;
                        $oandaTrade->units = abs((int) $t->units);
                        $oandaTrade->open_date_time = date("Y-m-d H:i:s", round((int) $t->time));

                        if ($t->units > 0) {
                            $oandaTrade->side = 1;
                        }
                        else {
                            $oandaTrade->side = -1;
                        }
                        $oandaTrade->price = $t->price;

                        if (isset($t->takeProfitPrice)) {
                            $oandaTrade->take_profit_diff = abs($t->price - $t->takeProfitPrice);
                        }

                        if (isset($t->stopLossPrice)) {
                            $oandaTrade->stop_loss_diff = abs($t->price - $t->stopLossPrice);
                        }
                        $oandaTrade->save();
                    }
                    elseif ($t->reason == 'MARKET_ORDER_POSITION_CLOSEOUT' || $t->reason == 'STOP_LOSS_ORDER' || $t->reason == 'TAKE_PROFIT_ORDER') {
                        if (isset($t->tradesClosed)) {
                            $oandaTrade = OandaTrades::firstOrNew(['oanda_open_id' => $t->tradesClosed[0]->tradeID]);
                        }

                        $oandaTrade->oanda_close_id = $t->id;

                        $oandaTrade->close_date_time = date("Y-m-d H:i:s", round((int) $t->time));

                        $oandaTrade->profit_loss = $t->pl;

                        $oandaTrade->close_price = $t->price;

                        $oandaTrade->close_reason = $t->reason;

                        $oandaTrade->account_balance = $t->accountBalance;

                        //$oandaTrade->interest = $t->interest;

                        $oandaTrade->save();
                    }
                }
                $lastProcessedId = $t->id;
            }
        }

        $accountUpdate = OandaAccounts::find($dbAccountId);

        $accountUpdate->last_transaction_pull = time();

        $accountUpdate->last_order_id = $lastProcessedId;

        $accountUpdate->save();

        $this->logger->processEnd();
    }

    public function saveLiveTransactions() {
        $this->logger = new ProcessLogger('fx_live_transactions');

        $this->environment = 'live';
        $this->liveTrading = 1;
        $this->getOandaTransactions();

        $this->logger->processEnd();
    }

    public function savePracticeTransactions() {
        $this->logger = new ProcessLogger('fx_practice_transactions');
        $this->environment = 'practice';
        $this->liveTrading = 0;
        $this->getOandaTransactions();
    }

    public function savePracticeTransactionsSpecificAccount($account) {
        $this->logger = new ProcessLogger('fx_practice_transactions');
        $this->environment = 'practice';
        $this->liveTrading = 0;
        $this->getOandaTransactions($account);
    }

//    public function getUnsavedTransactions() {
//
//        //Start Cron Job Tracker
//        $cronJobsTracker = new CronJobs(15);
//
//        Log::info('START: TransactionController->getUnsavedTransactions for account');
//
//        $next_account = OandaAccounts::orderBy('last_transaction_pull')
//            ->take(1)
//            ->get(['oanda_id', 'id']);
//
//            $next_account = $next_account->toArray()[0];
//
//            $oanada = new \App\Services\Oanda();
//            $oanada->accountId = $next_account['oanda_id'];
//
//            $oandaTransactionIds = [];
//
//            $oandaMaxOpen = OandaTrades::select(DB::raw('max(oanda_open_id) as max_open'))
//                ->where('oanda_account_id', '=', $next_account['oanda_id'])
//                ->take(1)
//                ->get();
//
//            $oandaMaxClose = OandaTrades::select(DB::raw('max(oanda_close_id) as max_close'))
//                ->where('oanda_account_id', '=', $next_account['oanda_id'])
//                ->take(1)
//                ->get();
//
//            $oandaMaxTradeOpen = OandaTrades::select(DB::raw('max(oanda_open_trade_id) as max_open_trade'))
//            ->where('oanda_account_id', '=', $next_account['oanda_id'])
//            ->take(1)
//            ->get();
//
//            $oandaMaxTradeClose = OandaTrades::select(DB::raw('max(oanda_close_trade_id) as max_close_trade'))
//                ->where('oanda_account_id', '=', $next_account['oanda_id'])
//                ->take(1)
//                ->get();
//
//            $oandaTransactionIds[] = $oandaMaxOpen->toArray()[0]['max_open'];
//            $oandaTransactionIds[] = $oandaMaxClose->toArray()[0]['max_close'];
//            $oandaTransactionIds[] = $oandaMaxTradeOpen->toArray()[0]['max_open_trade'];
//            $oandaTransactionIds[] = $oandaMaxTradeClose->toArray()[0]['max_close_trade'];
//
//            $maxId = max($oandaTransactionIds);
//
//            $oanadaResponse = $oanada->getTransactionsSince($maxId);
//
//            if (isset($oanadaResponse->transactions)) {
//                $transactions = array_reverse($oanadaResponse->transactions);
//
//                foreach ($transactions as $t) {
//
//                    //Figuring out why I have duplicate trades in the DB
//                    if ($t->id < $maxId) {
//                        Log::warning('Transaction Since id ID '.$t->id.' greater than DB max ID '.$maxId.'.');
//                    }
//                    if (isset($t->tradeId)) {
//                        if ($t->tradeId < $maxId) {
//                            Log::warning('Transaction Since trade ID '.$t->tradeId.' greater than DB max ID '.$maxId.'.');
//                        }
//                    }
//
//                    if ($t->type == "MARKET_ORDER_CREATE") {
//                        $oandaTrade = new OandaTrades();
//
//                        $oandaTrade->oanda_account_id = $oanada->accountId;
//                        $oandaTrade->oanda_open_id = $t->id;
//                        $oandaTrade->instrument = $t->instrument;
//                        $oandaTrade->units = $t->units;
//                        $oandaTrade->open_date_time = date("Y-m-d H:i:s", strtotime($t->time));
//
//                        if ($t->side == "sell") {
//                            $oandaTrade->side = -1;
//                        }
//                        else {
//                            $oandaTrade->side = 1;
//                        }
//                        $oandaTrade->price = $t->price;
//                        $oandaTrade->take_profit_diff = abs($t->price - $t->takeProfitPrice);
//                        $oandaTrade->stop_loss_diff = abs($t->price - $t->stopLossPrice);
//
//                        $oandaTrade->save();
//
//                    }
//                    elseif ($t->type == "STOP_LOSS_FILLED" || $t->type == "TAKE_PROFIT_FILLED") {
//
//                        $oandaTrade = OandaTrades::firstOrNew(['oanda_open_id' => $t->tradeId]);
//
//                        $oandaTrade->oanda_close_id = $t->id;
//
//                        $oandaTrade->close_date_time = date("Y-m-d H:i:s", strtotime($t->time));
//
//                        $oandaTrade->profit_loss = $t->pl;
//
//                        $oandaTrade->save();
//                    }
//                }
//            }
//
//
//            $processed_account = OandaAccounts::find($next_account['id']);
//            $processed_account->last_transaction_pull = time();
//            $processed_account->save();
//
//        Log::info('Get Oanda Transactions Finished for account '.$next_account['oanda_id'].'.');
//
//        $cronJobsTracker->end(1);
//    }

    public function tradeStats($trades) {
        $utility = new Utility();

        $sum = $utility->getSumOfArrayProperty($trades, 'profit_loss');

        return ['sum'=>$sum];
    }

    public function getTransactions($oandaAccountId) {
        $utility = new Utility();

        $lastDayPreviousMonth = new DateTime();
        $lastDayPreviousMonth->modify("last day of previous month");
        $lastDayPreviousMonth->format("Y-m-d");

        $trades = OandaTrades::where('open_date_time', '>', $lastDayPreviousMonth)
                       ->where('oanda_account_id', '=', $oandaAccountId)
                       ->orderBy('open_date_time', 'desc')
                       ->get()
                       ->toArray();

        $trades = array_map(function($t) {
            $t['open_date_human'] = date("n/j g:ha", strtotime($t['open_date_time']));
            $t['close_date_human'] = date("n/j g:ha", strtotime($t['close_date_time']));
            return $t;
        },$trades);

        $tradeStats = $utility->getSumOfArrayProperty($trades, 'profit_loss');

        $oandaAccounts = OandaAccounts::all()->toArray();

        return ['trades'=>$trades, 'tradeStats'=>$tradeStats, 'oandaAccounts'=>$oandaAccounts];
    }



    public function index() {
        return $this->getTransactions(3577742);
    }

    public function loadTransactions() {
        $post = Request::all();

        $account = OandaAccounts::where('oanda_id', '=', $post['account'])->firstOrFail();

//        if ((time() - $account->last_transaction_pull) < (60*60*30)) {
//            $this->getOandaTransactions($account->id);
//        }

        $oneMonthAgo = date('Y-m-d', strtotime('-30 day', time()));

        if (!$post['pastThisMonth']) {
            $trades = OandaTrades::where('oanda_account_id', '=', $post['account'])->where('open_date_time', '>', $oneMonthAgo)->orderBy('open_date_time', 'desc');
        }
        else {
            $trades = new OandaTrades();
        }

        if ($post['exchange'] != 'ALL') {
            $trades = $trades->where('instrument', '=', $post['exchange']);
        }

        if (isset($post['account'])) {
            $trades = $trades->where('oanda_account_id', '=', $post['account'])->orderBy('open_date_time', 'desc');
        }

        $trades = $trades->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return $trades;

    }

    public function getAccounts() {
        return OandaAccounts::get()->toArray();

    }
}