
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

    <!– Angular Factories and Services ->
    <script src="/js/factory/merchant.js"></script>
    <script src="/js/service/sweet_alert.js"></script>
    <script src="/js/factory/lookup.js"></script>
    <script src="/js/factory/back_test.js"></script>
    <script src="/js/factory/strategy.js"></script>
    <script src="/js/factory/strategy_system.js"></script>
    <script src="/js/factory/strategy_note.js"></script>
    <script src="/js/factory/transactions.js"></script>
    <script src="/js/service/utility.js"></script>
    <script src="/js/Chart_2.6.js"></script>
    <script src="/js/angular-chart.js"></script>
    <script src="/bower_components/angularUtils-pagination/dirPagination.js"></script>
    <script src="/js/modules/shared/directive/strategy-note-create/strategy-note-create.js"></script>
    <script src="/js/modules/shared/directive/strategy-note-view/strategy-note-view.js"></script>


    <!– Angular Controllers ->
    <script type="text/javascript" src="/js/controllers/side_nav.js"></script>
    <script type="text/javascript" src="/js/controllers/main.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/home.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/full.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/historical_data.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/create_backtest_group.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/high_low_analysis.js"></script>
    <script type="text/javascript" src="js/controllers/servers.js"></script>
    <script type="text/javascript" src="js/modules/historical_rates/run-historical-rates/run-historical-rates.js"></script>
    <script type="text/javascript" src="js/modules/strategy-log/strategy-log.js"></script>
    <script type="text/javascript" src="js/modules/strategy-create/strategy-create.js"></script>
    <script type="text/javascript" src="js/modules/strategy-management/strategy-management.js"></script>
    <script type="text/javascript" src="js/modules/strategy-system-create/strategy-system-create.js"></script>
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
                <a href="#strategy_management" class="waves-effect">
                    <i class="fas fa-align"></i>
                    <span> Strategy Management </span> <span class="pull-right"></span>
                </a>
            </li>
        </ul>
    </div>
@stop
