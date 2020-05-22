<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('welcome');


});

Route::get('auttth', function(){
    $user = Auth::user();
});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('home', 'UserController@home');


Route::get('log_transactions', 'TransactionController@getUnsavedTransactions');
Route::get('oanda_accounts', 'TransactionController@getAccounts');
Route::get('oanda_transactions_populate', 'TransactionController@getOandaTransactions');
Route::post('load_transactions', 'TransactionController@loadTransactions');
Route::get('unsaved_transactions', 'TransactionController@getUnsavedTransactions');

Route::get('graph', 'CurrencyController@graph');
Route::get('get_currency_data', 'CurrencyController@getCurrencyData');
Route::get('store_historical_data/{exchange}/{period}', 'CurrencyController@saveHistoricalDataDb');


/******************************
 * BACK TESTING
 ******************************/
//UI
Route::get('backTestStrategy', 'BackTestingController@backTestStrategy');
Route::get('back_test', 'BackTestingController@index');
Route::get('get_back_tests', 'BackTestingController@getBackTests');
Route::get('get_back_test_group_tests/{groupId}', 'BackTestingController@getBackTestsByGroupId');
Route::get('set_current_back_test/{backTestId}', 'BackTestingController@setBackTest');
Route::get('full_test_stats/{backTestId}', 'BackTestingController@fullTestStats');
Route::get('high_low_analysis/{backTestId}', 'BackTestingController@highLowAnalysis');
Route::get('back_test/gain_loss_analysis_low/{backtestId}', 'BackTestStatsController@gainLossAnalysisLow');
Route::get('back_test/gain_loss_analysis_high/{backtestId}', 'BackTestStatsController@gainLossAnalysisHigh');
Route::get('back_test_stats/roll_back_group/{backTestGroup}', 'BackTestStatsController@rollbackBackTestGroupStats');

//Call Systems
Route::get('/backtest/50_100_ema', 'BackTestingController@backTestFiftyOneHundred');
Route::get('/backtest/cowabunga', 'BackTestingController@cowabunga');
//Set Up
Route::get('/backtest_load_to_be_processed', 'BackTestingController@populateBackTestsToBeProcessedForStrategy');
Route::get('/evaluate_historical_data/{frequencyId}/{exchangeId}', 'HistoricalDataController@evaluateHistoricalData');
Route::get('/frequencies_exchanges', 'HistoricalDataController@getFrequenciesExchanges');
Route::get('/examine_currency_issues', 'HistoricalDataController@checkCurrencyIssues');
Route::get('/run_hd_cleanup', 'HistoricalDataController@populateSpotCheckHistoricalData');
Route::post('/back_test/create_group', 'BackTestingController@createGroup');
Route::delete('/back_test_iteration/{processId}', 'BackTestingController@rollbackSingleProcess');
Route::delete('/back_test_group/{backTestGroup}', 'BackTestingController@rollbackBackTestGroup');
Route::get('/back_test_group_populate_exchange', 'BackTestingController@populateBTGExchangeFrequency');

//Processed Individual Back Tests, for Debugging
Route::get('/call_fifty_auto_back_test', 'AutomatedBackTestController@processOneTestFiftyOneHundred');
Route::get('/call_hma_auto_back_test', 'AutomatedBackTestController@processOneHMaTrend');
Route::get('/call_high_low_back_process', 'AutomatedBackTestController@processHighLowBreakout');
Route::get('/ebt', 'AutomatedBackTestController@environmentVariableDriveProcess');
Route::get('/ebt_process/{processId}', 'AutomatedBackTestController@environmentVariableDriveProcessId');
Route::get('/auto_back_test_call', 'AutomatedBackTestController@runAutoBackTestIfFailsUpdate');

Route::get('/backtest_process_stats', 'BackTestStatsController@backtestProcessStats');
Route::get('/ebt_stats/{id}', 'BackTestStatsController@backtestProcessStatsSpecificProcess');
Route::get('indicator_run_through', 'BackTestingController@indicatorTest');
Route::get('manual_rollback', 'BackTestingController@manualRollback');
Route::get('manual_rollback_process', 'BackTestingController@manualRollbackProcess');
Route::get('manual_rollback_stats', 'BackTestingController@manualRollbackGroupStats');
Route::get('copy_back_test_process_to_other_exchanges/{processId}', 'BackTestManagementController@createBackTestGroupFromProcessIdToOtherExchanges');
Route::get('back_test_group_from_iteration/{processId}', 'BackTestManagementController@backTestGroupFromIteration');
Route::get('back_test_group_reviewed/{backTestGroupId}', 'BackTestManagementController@backTestGroupReviewed');
Route::get('delete_dev_test_back_tests', 'BackTestingController@deleteDevTestOnlyBackTestGroups');
Route::get('back_test_group_profitable/{backTestGroup}', 'BackTestingController@markBackTestGroupProfitable');


/******************************
 * LIVE PRACTICE SYSTEMS
 ******************************/
Route::get('call_cowabunga', 'LivePracticeController@cowabunga');
Route::get('call_hlhb', 'LivePracticeController@hlhb');
Route::get('call_simple_macd', 'LivePracticeController@simpleMacd');
Route::get('call_outer_macd', 'LivePracticeController@macdTwoTier');
Route::get('call_fifty_onehundred', 'LivePracticeController@emaMomentumHourly');
Route::get('call_hourly', 'LivePracticeController@hourly');
Route::get('call_hma', 'LivePracticeController@hma');
Route::get('ema_momentum_fifteen', 'LivePracticeController@emaMomentumFifteenMinutes');
Route::get('ema_momentum_fifteen_tpsl', 'LivePracticeController@emaMomentumFifteenMinutesTPSL');
Route::get('hma_twolevel', 'LivePracticeController@twoLevelHmaDaily');
Route::get('hma_four', 'LivePracticeController@hmaAdxStayInFourHour');
Route::get('ema_adx_fifteen', 'LivePracticeController@emaMomentumAdx15MinutesTPSL');
Route::get('call_high_low_breakout', 'LivePracticeController@highLowBreakout');
Route::get('live_test_sandbox', 'LivePracticeController@testSandBoxAccount');
Route::get('lp_test_account', 'LivePracticeController@testAccountActions');
Route::get('lp_thirty', 'LivePracticeController@thirtyMinute');
Route::get('fifteenEmaFiveTenBefore', 'LivePracticeController@fifteenEmaFiveTenBefore');
Route::get('hourlyStochPullback', 'LivePracticeController@hourlyStochPullback');
Route::get('fifteenMinuteStochPullback', 'LivePracticeController@fifteenMinuteStochPullback');
Route::get('emaXAdxConfirmWithMarketIfTouched', 'LivePracticeController@emaXAdxConfirmWithMarketIfTouched');
Route::get('hmaFifteenMinutes', 'LivePracticeController@hmaFifteenMinutes');

/******************************
 * SERVERS
 ******************************/
Route::get('servers', 'ServersController@index');
Route::post('servers', 'ServersController@updateServers');
Route::post('servers_copy_local', 'ServersController@updateLocalToServer');
Route::post('servers_copy_from_local', 'ServersController@updateSeverToBeLocal');
Route::post('update_server', 'ServersController@updateSingleServer');
Route::get('get_next_backtest', 'ServersController@getNextBackTestGroupForServer');
Route::get('set_server_environment', 'ServersController@setServerEnvironment');


Route::get('oanda_test', 'TestController@testGetRates');
Route::get('two_rates_test', 'TestController@testGetRatesTwoTierLooping');
Route::get('test_decision', 'TestController@testDecision');
Route::get('test_oanda_price', 'TestController@testOandaPricing');
Route::get('test_mail', 'TestController@testMail');
Route::get('test_twilio', 'TestController@testTwilio');
Route::get('test_macd', 'TestController@macd');

Route::get('testabcasdf', 'LivePracticeController@testabcasdf');

Route::get('createUser', 'UserController@createUser');



Route::get('call_cowabunga', 'LivePracticeController@cowabunga');
Route::get('call_hlhb', 'LivePracticeController@hlhb');
Route::get('call_simple_macd', 'LivePracticeController@simpleMacd');
Route::get('call_outer_macd', 'LivePracticeController@macdTwoTier');


Route::get('close_position_test', 'LivePracticeController@closeTest');

Route::get('dash_load', 'TransactionController@index');
Route::get('transactions/{oandaAccountId}', 'TransactionController@getTransactions');

Route::get('historical_time_stamp', 'CurrencyController@populateHistoricalTimeStamp');
Route::get('transactions', 'TransactionController@getTransactions');
Route::get('testAccountINfo', 'LivePracticeController@testAccountINfo');

Route::get('all_accounts', 'AccountsController@createNewAccounts');

Route::get('quick_tes123t', 'RentalController@createRentalTable');
Route::get('load_rentals', 'RentalController@loadRentals');



/******************************
 * HISTORICAL RATES
 ******************************/
Route::get('test_historical_data', 'HistoricalDataController@populateHistoricalData');
Route::get('test_historical_volume', 'HistoricalDataController@initialLoad');
Route::get('test_most_recent', 'HistoricalDataController@historicalDataPull');
Route::get('h_d_test', 'HistoricalDataController@test');
Route::get('historical_rates/run_specific/{frequencyId}/{currencyId}', 'HistoricalDataController@populateHistoricalDataSpecific');
Route::get('test_log', 'TestController@testLog');


/******************************
 * STOCKS
 ******************************/
Route::get('stocks_historical_data', 'Equity\StocksHistoricalDataController@getStockData');
Route::get('stocks/industry', 'Equity\StockIndustryController@index');
Route::get('stocks/sectors', 'Equity\StockSectorController@index');
Route::get('stocks/technical_checks', 'Equity\StockTechnicalCheckController@index');
Route::get('stocks/technical_check_variables/{technicalCheckId}', 'Equity\StockTechnicalCheckController@getVariables');
Route::get('stocks/company_profile/{stockId}', 'Equity\StocksCompanyProfileController@getProfile');
Route::get('stocks/financial/{stockId}', 'Equity\StockSectorController@getFinancial');
Route::get('stocks/rates/{symbol}/{chartPeriod}', 'Equity\StockRatesController@getChart');
Route::get('stocks/rates_profile', 'Equity\StockRatesController@ratesProfile');
Route::post('stocks/search', 'Equity\StockSearchController@index');
Route::post('stocks/back_test', 'Equity\StockBacktestController@createBacktest');
Route::get('stocks/backtest_groups', 'Equity\StockBacktestController@backtestGroups');
Route::get('stocks/backtest_group_iterations/{groupId}', 'Equity\StocksBacktestStatsControllerf@getBacktestIterationResults');


/******************************
 * STRATEGY LOGS
 ******************************/
Route::get('strategy_logger', 'StrategyLogController@index');
Route::get('strategy_logger/log_history/{account}/{exchange}/{dateTime}/{onlyEvents}', 'StrategyLogController@getLogs');
Route::get('strategy_logger/log_messages/{logId}', 'StrategyLogController@getLogMessages');
Route::get('strategy_logger/log_api/{logId}', 'StrategyLogController@getLogApi');
Route::get('strategy_logger/log_indicators/{logId}', 'StrategyLogController@getLogIndicators');
Route::get('strategy_logger/log_history_subset/{pageNumber}/{account}/{exchange}/{dateTime}/{onlyEvents}', 'StrategyLogController@getLogsSubset');

Route::get('process_logger', 'ProcessLogController@index');
Route::post('process_logger/load_logs', 'ProcessLogController@getLogs');
Route::get('process_log/{logId}', 'ProcessLogController@getLog');
Route::post('process_log_messages', 'ProcessLogController@getLogMessages');

//Route::resource('strategy', 'StrategyController');
Route::resource('indicator', 'IndicatorsController');
Route::resource('indicator_event', 'IndicatorsEventsController');
Route::get('indicator_events/{indicatorId}', 'IndicatorsEventsController@allIndicatorEventsForIndicator');
Route::get('indicator_event_types', 'IndicatorsEventsController@getIndicatorEventTypes');
Route::resource('strategy', 'StrategyController');
Route::get('strategy_systems/{strategyId}', 'StrategySystemController@strategySystems');
Route::get('load_notes/{strategyId}', 'StrategyNotesController@loadNotes');
Route::resource('strategy_system', 'StrategySystemController');
Route::resource('strategy_notes', 'StrategyNotesController');


Route::post('/rentals/load', 'Marketing\HarRentalController@load');
Route::post('/lustre/load', 'ProductScrapingController@lustreDump');
Route::get('/specific_rental_curl', 'Marketing\RentalController@testCurl');
Route::get('/rentals/send_email', 'Marketing\HarRentalController@getRentalEmail');
Route::get('/getListings', 'Marketing\RentalController@getListings');



Route::get('/ade', 'Equity\YahooFinanceController@createRecentUpdateRecords');

Route::get('php', function () {
    echo phpinfo();
});

Route::get('env', function () {
    echo env('APP_ENV');
});

Route::get('test_text', 'StrategySystemController@strategySystems');

//TBD Test
Route::get('td_ameritrade_auth_code', function() {

});
//TBD Test
Route::get('testdec', function() {
    echo time() - 1552501676;
});



Route::post('login_attempt', 'UserController@loginAttempt');

//Back Test Routes
Route::get('backTestStrategy', 'BackTestingController@backTestStrategy');
Route::get('back_test', 'BackTestingController@index');
Route::get('get_back_test_data/{backTestId}', 'BackTestingController@getBackTestData');

//\Blade::setContentTags('<%', '%>');
//\Blade::setEscapedContentTags('<%%', '%%>');


Route::get('testrates', function() {

    $historicalRates = new App\Model\HistoricalRates();
    $rates = $historicalRates->getRatesSpecificTimeSimple(1,1,100,'2017-03-28 13:30:00');

    foreach ($rates as $rate) {
        echo $rate."<BR>";
    }
});

Route::get('/nurse_jobs', function () {
    return \App\Model\NurseJobs::orderBy('state')->get()->toArray();
});

Route::get('debug_aws_servers', 'ServersController@updateEnvironmentDBHost');

Route::auth();