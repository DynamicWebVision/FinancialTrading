
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



    <!– Angular Controllers ->
    <script type="text/javascript" src="/js/controllers/nurse_jobs_controller.js"></script>
@stop

@section('side_nav')
    <div id="sidebar-menu" ng-controller="SideNavController">
        {{test}}
    </div>
@stop
