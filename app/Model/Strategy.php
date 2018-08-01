<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use \DB;

class Strategy extends Model {

    protected $table = 'strategy';

    public function getExchanges($strategyId) {
        return DB::select("select exchange.* from strategy_exchange_rules
                    join exchange on exchange.id = strategy_exchange_rules.exchange_id
                    where strategy_exchange_rules.strategy_id = ? 
                    order by strategy_exchange_rules.rank;", [$strategyId]);
    }
}
