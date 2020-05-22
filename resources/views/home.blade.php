
@extends('admin_app_template')

@section('css')
    <link rel="stylesheet" type="text/css" href="css/style.css">
@stop

@section('title')
    Currency Dashboard
@stop

@section('javascript')
    <script src="/js/angular-animate.js"></script>
    <script src="/js/app/home_app.js"></script>
    <script src="/js/angular_directives.js"></script>
    <script src="/bower_components/json-formatter/src/json-formatter.js"></script>
    <script src="/bower_components/json-formatter/src/recursion-helper.js"></script>
    <script src="bower_components/angular-ui-select/dist/select.js"></script>
    <link rel="stylesheet" type="text/css" href="bower_components/angular-ui-select/dist/select.css">
    <script src="/bower_components/json-formatter/src/recursion-helper.js"></script>

    <!– Angular Factories and Services ->
    <script src="/js/factory/merchant.js"></script>
    <script src="/js/service/sweet_alert.js"></script>
    <script src="/js/factory/lookup.js"></script>
    <script src="/js/factory/back_test.js"></script>
    <script src="/js/factory/strategy.js"></script>
    <script src="/js/factory/indicator.js"></script>
    <script src="/js/factory/indicator_event.js"></script>
    <script src="/js/factory/strategy_system.js"></script>
    <script src="/js/factory/stock-financial.js"></script>
    <script src="/js/factory/strategy_note.js"></script>
    <script src="/js/factory/transactions.js"></script>
    <script src="/js/factory/stock.js"></script>
    <script src="/js/factory/stock-rates.js"></script>
    <script src="/js/factory/stock-search.js"></script>
    <script src="/js/factory/stock-industry.js"></script>
    <script src="/js/factory/stock-sector.js"></script>
    <script src="/js/modules/stocks/factory/stocks-backtest-groups.js"></script>
    <script src="/js/service/utility.js"></script>
    <script src="/js/service/clipboard.js"></script>
    <script src="/js/Chart_2.6.js"></script>
    <script src="/js/angular-chart.js"></script>
    <script src="/bower_components/angularUtils-pagination/dirPagination.js"></script>
    <script src="/js/modules/shared/directive/strategy-note-create/strategy-note-create.js"></script>
    <script src="/js/modules/shared/directive/strategy-note-view/strategy-note-view.js"></script>
    <script src="/js/modules/shared/directive/indicator-event-select/indicator-event-select.js"></script>
    <script src="/js/modules/shared/directive/stock-chart/stock-chart.js"></script>
    <script src="/js/modules/shared/directive/stock-company-profile/stock-company-profile.js"></script>


    <!– Angular Controllers ->
    <script type="text/javascript" src="/js/controllers/side_nav.js"></script>
    <script type="text/javascript" src="/js/controllers/main.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/home.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/full.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/historical_data.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/create_backtest_group.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/high_low_analysis.js"></script>
    <script type="text/javascript" src="js/modules/stocks/stocks-backtest-list/stocks-backtest-list.js"></script>
    <script type="text/javascript" src="js/controllers/servers.js"></script>
    <script type="text/javascript" src="js/modules/historical_rates/run-historical-rates/run-historical-rates.js"></script>
    <script type="text/javascript" src="js/modules/strategy-log/strategy-log.js"></script>
    <script type="text/javascript" src="js/modules/process-log/process-log.js"></script>
    <script type="text/javascript" src="js/modules/strategy-create/strategy-create.js"></script>
    <script type="text/javascript" src="js/modules/indicator-create/indicator-create.js"></script>
    <script type="text/javascript" src="js/modules/indicator-event-create/indicator-event-create.js"></script>
    <script type="text/javascript" src="js/modules/strategy-management/strategy-management.js"></script>
    <script type="text/javascript" src="js/modules/strategy-system-create/strategy-system-create.js"></script>
    <script type="text/javascript" src="js/modules/indicators/indicators-management.js"></script>
    <script type="text/javascript" src="js/modules/stocks/stocks-main/stocks-main.js"></script>
    <script type="text/javascript" src="js/modules/stocks/stocks-create-backtest/stocks-create-backtest.js"></script>
    <script type="text/javascript" src="js/modules/stocks/factory/stocks-technical-check.js"></script>
    <script type="text/javascript" src="js/modules/nurse-jobs/nurse-jobs.js"></script>
    <script type="text/javascript" src="js/modules/marketing/rentals/rentals.js"></script>
    <script type="text/javascript" src="js/modules/marketing/lustre-dump/lustre-dump.js"></script>
@stop

@section('side_nav')
    <div id="sidebar-menu" ng-controller="SideNavController">
        <ul>
            <li>
                <a href="#home" class="waves-effect">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <span> Main </span> <span class="pull-right"></span>
                </a>
            </li>
            <li ng-class="{'active': openUrl == 'stocks'}">

                <a href="#/stocks_main" class="waves-effect"
                   ng-click="openUrl = 'stocks'">
                    <i class="fa fa-bell-o" aria-hidden="true"></i>
                    <span> Stocks</span> <span class="pull-right"></span>
                </a>
                <ul ng-cloak class="list-unstyled" ng-show="openUrl == 'stocks'">
                    <li>
                        <a href="#/stocks/create_backtest" class="waves-effect">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            <span> Create Backtest </span>
                            <span class="pull-right"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#/stocks/backtest_list" class="waves-effect">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            <span> Backtest Results</span>
                            <span class="pull-right"></span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#back_test_home" class="waves-effect">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                    <span> Back Tests </span> <span class="pull-right"></span>
                </a>
            </li>
            <li>
                <a href="#servers" class="waves-effect">
                    <i class="fa fa-server" aria-hidden="true"></i>
                    <span> Servers </span> <span class="pull-right"></span>
                </a>
            </li>
            <li>
                <a href="#create_backtest_group" class="waves-effect">
                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                    <span> Create Back Test </span> <span class="pull-right"></span>
                </a>
            </li>
            <li>
                <a href="#historical_rates" class="waves-effect">
                    <i class="fas fa-history"></i>
                    <span> Historical Rates </span> <span class="pull-right"></span>
                </a>
            </li>
            <li>
                <a href="#strategy_logger" class="waves-effect">
                    <i class="fas fa-align"></i>
                    <span> Strategy Logger </span> <span class="pull-right"></span>
                </a>
            </li>
            <li>
                <a href="#process_logger" class="waves-effect">
                    <i class="fas fa-align"></i>
                    <span> Process Logger </span> <span class="pull-right"></span>
                </a>
            </li>
            <li>
                <a href="#strategy_management" class="waves-effect">
                    <i class="fas fa-align"></i>
                    <span> Strategy Management </span> <span class="pull-right"></span>
                </a>
            </li>
            <li>
                <a href="#indicator_management" class="waves-effect">
                    <i class="fas fa-align"></i>
                    <span> Indicator Management </span> <span class="pull-right"></span>
                </a>
            </li>
            <li>
                <a href="#nurse_jobs_view" class="waves-effect">
                    <i class="fas fa-align"></i>
                    <span> Nursing Contracts </span> <span class="pull-right"></span>
                </a>
            </li>
            <li ng-class="{'active': openUrl == 'marketing'}">

                <a href="#/marketing/rentals" class="waves-effect"
                   ng-click="openUrl = 'marketing'">
                    <i class="fa fa-bell-o" aria-hidden="true"></i>
                    <span> Marketing</span> <span class="pull-right"></span>
                </a>
                <ul ng-cloak class="list-unstyled" ng-show="openUrl == 'stocks'">
                    <li>
                        <a href="#/marketing/rentals" class="waves-effect">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            <span> Rentals </span>
                            <span class="pull-right"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
@stop
