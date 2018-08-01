
@extends('admin_app_template')

@section('css')
    <link rel="stylesheet" type="text/css" href="css/style.css">
@stop

@section('title')
    Currency Backtesting
@stop

@section('javascript')
    <script src="/js/app/back_test_app.js"></script>
    <script src="/js/angular_directives.js"></script>

    <!– Angular Factories and Services ->
    <script src="/js/factory/merchant.js"></script>
    <script src="/js/service/sweet_alert.js"></script>
    <script src="/js/factory/lookup.js"></script>
    <script src="/js/factory/back_test.js"></script>
    <script src="/js/factory/transactions.js"></script>
    <script src="/js/service/utility.js"></script>
    <script src="/js/Chart_2.6.js"></script>
    <script src="/js/angular-chart.js"></script>

    <!– Angular Controllers ->
    <script type="text/javascript" src="js/controllers/back_test/home.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/full.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/historical_data.js"></script>
    <script type="text/javascript" src="js/controllers/back_test/create_backtest_group.js"></script>
@stop

@section('side_nav')
    <div id="sidebar-menu" >
        <ul>
            <li>
                <a href="#create_backtest_group" class="waves-effect">
                    <i class="fa fa-info" aria-hidden="true"></i>
                    <span> Create BT Group </span> <span class="pull-right"></span>
                </a>
            </li>
        </ul>
    </div>
@stop