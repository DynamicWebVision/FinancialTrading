<html lang="en"  ng-app="app">
	<head>
        <link rel="stylesheet/less" type="text/css" href="fonts/font_definitions.css">
        <script type="text/javascript" src="javascript/jquery.js"></script>
        <script type="text/javascript" src="javascript/angular.js"></script>
		<script type="text/javascript" src="/javascript/angular_animate.js"></script>
		<script type="text/javascript" src="javascript/base.js"></script>

		<script type="text/javascript" src="javascript/currency_base.js"></script>
		<script type="text/javascript" src="javascript/currency/currency_factory.js"></script>
		<script type="text/javascript" src="javascript/currency/graph_controller.js"></script>
		<link rel="stylesheet" type="text/css" media="all" href="css/graph.css">
	</head>
	<body>

		<div ng-controller="GraphController">

        <div class="">
            <table>
                <tr>
                    <td class="statLabel">Total Positions</td>
                    <td>{{stats.totalPositions}}</td>
                    <td class="statLabel">5 Day Change Average</td>
                    <td>{{stats.fiveDayChangeAverage}}</td>
                    <td class="statLabel">10 Day Change Average</td>
                    <td>{{stats.tenDayChangeAverage}}</td>
                    <td class="statLabel">Average Position</td>
                    <td>{{stats.average_position}}</td>
                    <td class="statLabel">Median Position</td>
                    <td>{{stats.median_position}}</td>
                </tr>
                <tr>
                    <td class="statLabel">Total Gains</td>
                    <td class="gain">{{stats.totalGains}}</td>
                    <td class="statLabel">5 Day Change Median</td>
                    <td>{{stats.fiveDayChangeMedian}}</td>
                    <td class="statLabel">10 Day Change Median</td>
                    <td>{{stats.tenDayChangeMedian}}</td>
                    <td class="statLabel">Average Gain</td>
                    <td>{{stats.averageGain}}</td>
                    <td class="statLabel">Median Gain</td>
                    <td>{{stats.medianGain}}</td>
                </tr>
                <tr>
                    <td class="statLabel">Total Losses</td>
                    <td class="loss">{{stats.totalLosses}}</td>
                    <td class="statLabel">5 Day Change Probability</td>
                    <td>{{stats.fiveDayChangeProbability}}</td>
                    <td class="statLabel">10 Day Change Probability</td>
                    <td>{{stats.tenDayChangeProbability}}</td>
                    <td class="statLabel">Average Loss</td>
                    <td>{{stats.averageLoss}}</td>
                    <td class="statLabel">Median Loss</td>
                    <td>{{stats.medianLoss}}</td>
                </tr>
                <tr>
                    <td class="statLabel">Net Gains/Losses</td>
                    <td>{{stats.netPositiveNegative}}</td>

                    <td class="statLabel">Net Percent</td>
                    <td>{{stats.netPercent}}</td>

                    <td class="statLabel">Lots Gains/Losses</td>
                    <td>{{stats.netLotsGainLoss}}</td>
                    <td class="statLabel">Ten Percent Margin</td>
                    <td>{{tenMarginAmount}}</td>


                </tr>
            </table>
        </div>


		<div style="position: fixed; top: 20px; right: 20px; width: 400px; height: 200px;">
			{{mousePos.x}}-{{mousePos.y}}<BR>
			{{mouseDateTime}}<BR>
			{{mouseTradePrice}}<BR>
			{{mouseRsi}} rsi<BR>
			{{mouseTradePrice}}<BR>
			{{mouseMacd}} macd<BR>
			{{mouseMacdIndicator}} macd MA<BR>
            {{mouseRoc}} roc<BR>
            {{mouseIndex}} index
		</div>

        <div class="graphShowIndicators">
            <input type="checkbox" name="bollinger" ng-model="showBollinger" ng-click="graphData()">
            Bollinger
            <BR>
            <input type="checkbox" name="sma" ng-model="showSma" ng-click="graphData()">
            SMA
            <BR>
            <input type="checkbox" name="sma" ng-model="showEma" ng-click="graphData()">
            EMA
        </div>

        <canvas id="graph" height="450" width="30000" ng-mousemove="graphHover($event)">

        </canvas>
        <div id="currencyGraph">
            <div ng-repeat="transaction in transactions">
                <div class="transactionShow" style="left: {{transactionShowLeft(transaction.rateIndex)}}px">
                    {{transaction.transactionDate}}<BR>
                    {{transaction.price}}<BR>
                    <span ng-show="transaction.transactionType == 'closeLong' || transaction.transactionType == 'closeShort'">{{transaction.gainLossPercent}}%</span><BR>
                    <span ng-show="transaction.transactionType == 'closeLong' || transaction.transactionType == 'closeShort'">${{transaction.oneLotGainLoss}}</span>
                </div>
            </div>
        </div>


        <canvas id="graphRSI" height="100" ng-mousemove="graphRsiHover($event)" width="30000">
        </canvas>

        <canvas id="graphMacd" height="150" width="30000" ng-mousemove="graphMacdHover($event)">
        </canvas>

        <div style="position: fixed; top: 600px; right: 20px; width: 400px; height: 200px; color: purple;">
            {{mouseMacdHisto}}
        </div>




		<table class="positionTable">
            <tr ng-repeat="transaction in transactions">
                <td>{{transaction.transactionDate}}</td>
                <td>{{transaction.transactionType}}</td>
                <td>{{transaction.price}}</td>
                <td class="gainLossClass(transaction.gainLoss)">{{transaction.gainLoss}}</td>
                <td class="gainLossClass(transaction.gainLoss)">{{transaction.oneLotGainLoss}}</td>
                <td>{{transaction.tenPercentMarginAmount}}</td>
            </tr>
		</table>
		</div>
	</body>
</html>
