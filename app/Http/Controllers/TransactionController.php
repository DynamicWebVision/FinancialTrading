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

class TransactionController extends Controller {

    public function getOandaTransactions($account = false) {

        if ($account) {
            $account = OandaAccounts::find($account);
            $id = $account->oanda_id;
            $dbAccountId = $account->id;
        }
        else {
            $nextAccount = OandaAccounts::orderBy('last_transaction_pull')
                ->take(1)
                ->get(['oanda_id', 'id']);

            $id = $nextAccount[0]->oanda_id;
            $dbAccountId = $nextAccount[0]->id;
        }

        $broker = new OandaV20();

        $runId = uniqid();

        Log::info('Get Transactions Start - '.$runId);


        Log::info('Get Transactions Start for Account '.$id.' - '.$runId);

        $broker->accountId = $id;

        $minId = OandaTrades::where('oanda_account_id', '=', $id)->max('oanda_open_id');
        $minId = 2900;

        if ($minId != null) {
            $transactions = $broker->getTransactionsSince($minId);
        }
        else {
            $transactions = $broker->getTransactionHistory();
        }

        \DB::insert('insert into oanda_transactions_dump (response) values (?);', [json_encode($transactions)]);

        Log::info('Get Transactions Response Account '.$id.' Oanda Response - '.$runId.PHP_EOL.json_encode($transactions));

        if (!isset($transactions)) {
            Log::info('No Transactions '.$id.' Oanda Response - '.$runId);
        }

      //  $transactions = array_reverse($oanadaResponse->transactions);
        if (sizeof($transactions) > 0) {
            foreach ($transactions as $t) {
                if ($t->type == "ORDER_FILL") {

                    if ($t->reason == 'MARKET_ORDER' || $t->reason == 'LIMIT_ORDER') {
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
                        else {
                            $debug = true;
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
            }
        }

        $accountUpdate = OandaAccounts::find($dbAccountId);

        $accountUpdate->last_transaction_pull = time();

        $accountUpdate->save();

        Log::info('Get Transactions FINISH for Account '.$id.' - '.$runId);
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
                       ->orderBy('id', 'desc')
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

        if ((time() - $account->last_transaction_pull) < (60*60*30)) {
            $this->getOandaTransactions($account->id);
        }

        $oneMonthAgo = date('Y-m-d', strtotime('-30 day', time()));

        if (!$post['pastThisMonth']) {
            $trades = OandaTrades::where('open_date_time', '>', $oneMonthAgo)->orderBy('id', 'desc');
        }
        else {
            $trades = new OandaTrades();
        }

        if ($post['exchange'] != 'ALL') {
            $trades = $trades->where('instrument', '=', $post['exchange']);
        }

        if (isset($post['account'])) {
            $trades = $trades->where('oanda_account_id', '=', $post['account'])->orderBy('id', 'desc');
        }

        $trades = $trades->orderBy('id', 'desc')
            ->get()
            ->toArray();

        return $trades;

    }

    public function getAccounts() {
        return OandaAccounts::get()->toArray();

    }

    public function playWithOandaJsonData() {
        $test = 1;
        $data = json_decode(file_get_contents('/Users/boneill/ReferenceWorkspaces/CurrencyTrading/Tmp/test.json'));

        foreach ($data as $index=>$transaction) {
            if ($transaction->id == 2939) {
                $debug= 1;
            }

            if (isset($transaction->orderID)) {
                if ($transaction->orderID == '2939') {
                    $debug = 1;
                }
            }
        }
    }
}