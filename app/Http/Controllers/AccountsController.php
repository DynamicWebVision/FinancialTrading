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

class AccountsController extends Controller {

    public function createNewAccounts() {
        $oanda = new OandaV20();
        $accounts = $oanda->allAccounts();

        foreach ($accounts as $account) {
            $savedAccount = OandaAccounts::firstOrNew(['oanda_id'=>$account->id]);

            $savedAccount->oanda_id = $account->id;

            $savedAccount->account_name = $account->alias;

            $savedAccount->balance = round($account->balance);

            $savedAccount->save();
        }
    }
}