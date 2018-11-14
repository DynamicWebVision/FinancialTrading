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

    public $accountLive;
    public $environment;

    public function createNewAccounts() {

        $oanda = new OandaV20($this->environment);
        $accounts = $oanda->allAccounts();

        foreach ($accounts as $account) {
            $savedAccount = OandaAccounts::firstOrNew(['oanda_id'=>$account->id]);

            $savedAccount->oanda_id = $account->id;

            $savedAccount->account_name = $account->alias;

            $savedAccount->balance = round($account->balance);

            $savedAccount->live_trading = $this->accountLive;

            $savedAccount->save();
        }
    }

    public function createNewLiveAccounts() {
        $this->accountLive = 1;
        $this->environment = 'live';
        $this->createNewAccounts();
    }

    public function createNewPracticeAccounts() {
        $this->accountLive = 0;
        $this->environment = 'practice';
        $this->createNewAccounts();
    }
}