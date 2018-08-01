/**
 * Created by brianoneill on 2/19/15.
 */

app.controller('GraphController', function(Currency, $scope, $http) {
	// instantiate a new user
	var currency;
    $scope.rates = [];

    var maxMacd;
    var minMacd;
    var macdValueHeight;

    var rsiUpper;
    var rsiLower;
    var rsiMid;

    $scope.drawGraphLine = function(oldX, oldY, newX, newY, lineColor) {
        var c=document.getElementById("graph");
        var ctx=c.getContext("2d");
        ctx.strokeStyle=lineColor;
        ctx.beginPath();
        ctx.moveTo(parseInt(oldX),parseInt(450-oldY));
        ctx.lineTo(parseInt(newX), parseInt(450-newY));
        ctx.stroke();
    }

    $scope.drawRsiLine = function(oldX, oldY, newX, newY, lineColor) {
        var c=document.getElementById("graphRSI");
        var ctx=c.getContext("2d");
        ctx.strokeStyle=lineColor;
        ctx.beginPath();
        ctx.moveTo(oldX,100-oldY);
        ctx.lineTo(newX,100-newY);
        ctx.stroke();
    }

    $scope.drawMacdLine = function(oldX, oldY, newX, newY, lineColor) {

        var cMacd =document.getElementById("graphMacd");
        var macdCtx=cMacd.getContext("2d");
        macdCtx.strokeStyle=lineColor;
        macdCtx.beginPath();
        macdCtx.moveTo(parseInt(oldX),parseInt(macdGraphHeight-oldY));
        macdCtx.lineTo(parseInt(newX),parseInt(macdGraphHeight-newY));
        macdCtx.stroke();
    }

    $http.get('/get_currency_data').success(function(data){
        currency = data;
        document.title = 'LOADED';

        $scope.rates = currency.rates;
        $scope.graphRates = $scope.rates;

        $scope.graphWidth = currency.rateCount*5;
        $scope.minRate = currency.minRate;
        $scope.maxRate = currency.maxRate;

        maxMacd = currency.maxMacd;
        minMacd = currency.minMacd;
        macdValueHeight = maxMacd - minMacd;
        $scope.tenMarginAmount = currency.tenMarginAmount;

        rsiUpper = currency.rsiUpperCutoff;
        rsiLower = currency.rsiLowerCutoff;

        rsiMid = 50;

        $scope.transactions = currency.transactions;
        $scope.stats = currency.transactionStats;

        console.log(currency.rates);

        $scope.graphCurMax = Math.max.apply(Math,currency.rates.map(function(o){return o.amount;}))*1.02;

        $scope.graphCurMin = Math.min.apply(Math,currency.rates.map(function(o){return o.amount;}))*.98;

        $scope.graphData();
    });


	$scope.graphHeight = 450;

    $scope.graphRsiHeight = 100;
    var macdGraphHeight = 150;


	$scope.mousePos = {};
	$scope.mouseTradePrice = "";

	$scope.mouseDateTime = "";
    $scope.mouseRsi = "";
    $scope.mouseMacd = "";
    $scope.mouseMacdIndicator = "";

    $scope.ratesBegin = 100;
    $scope.ratesEnd = 200;

    $scope.graphRates = [];

    $scope.showBollinger = false;
    $scope.showSma = false;
    $scope.showEma = false;

	$scope.graph = [];

    var c = document.getElementById("graph");
    var ctx = c.getContext("2d");


    var cMacd = document.getElementById("graphMacd");
    var ctxMacd = cMacd.getContext("2d");



	$scope.calculateVert = function(rate) {
        var curHeight = parseFloat($scope.graphCurMax) - parseFloat($scope.graphCurMin);
        var rateValue = rate - $scope.graphCurMin;

        return Math.round(((($scope.graphHeight*rateValue)/curHeight) + parseFloat($scope.graphCurMin)));
    }

    $scope.calculateRsiVert = function(rate) {
        var valueHeight = 100;
        var rateValue = rate;

        return Math.round(((($scope.graphRsiHeight*rateValue)/valueHeight)));
    }

    $scope.calculateMacdVert = function(rate) {
        var rateValue = parseFloat(rate) - minMacd;

        return Math.round((rateValue/macdValueHeight)*macdGraphHeight);

    }

	$scope.graphData = function() {
        var indx = 5;
        var previousVirt = 0;
        var newVirt = 0;

        var newUpperBolVirt = 0;
        var previousUpperBollingerVirt = 0;
        var newLowerBolVirt = 0;
        var previousLowerBollingerVirt = 0;

        var previousShortSmaVirt = 0;
        var newShortSmaVirt = 0;
        var previousLongSmaVirt = 0;
        var newLongSmaVirt = 0;

        var previousRsi = 0;
        var newRsi = 0;
        var newMacd = 0;
        var newSignal = 0;
        var previousMacd = 0;
        var previousSignal = 0;
        var positionVert = 0;

        var c = document.getElementById("graph");
        var ctx = c.getContext("2d");
        ctx.beginPath();    // clear existing drawing paths
        ctx.save();

        ctx.clearRect(0, 0, $("#graph").width(), $("#graph").height());

        for (i = 0, len = $scope.graphRates.length, text = ""; i < len; i++) {

            newVirt = $scope.calculateVert($scope.graphRates[i].amount);

            $scope.graph[indx] = {
                "graphVirt": newVirt,
                "tradeDate": $scope.graphRates[i].tradeDate,
                "tradeAmount": $scope.graphRates[i].amount,
                "rsi": $scope.graphRates[i].rsi,
                "macd": $scope.graphRates[i].macd,
                "macdIndicator": $scope.graphRates[i].macdIndicator,
                "roc": $scope.graphRates[i].roc,
                "index": i,
                "macdHistogram": $scope.graphRates[i].macdHistogram
            }

            if (indx > 5) {
                prevIndx = indx-5;
                $scope.drawGraphLine(prevIndx, previousVirt, indx, newVirt, "#0033CC");

                //Show Positions Taken
                if ($scope.graphRates[i].newPosition == "long") {

                    positionVert = parseInt(newVirt) + 15;
                    $scope.drawGraphLine(indx, newVirt, indx, positionVert, "#008000");
                }
                else if ($scope.graphRates[i].newPosition == "short") {
                    positionVert = parseInt(newVirt) - 15;
                    $scope.drawGraphLine(indx, newVirt, indx, positionVert, "#008000");
                }

                if ($scope.graphRates[i].closePosition == "closeLong") {
                    positionVert = parseInt(newVirt) - 15;
                    $scope.drawGraphLine(indx, newVirt, indx, positionVert, "#ff0000");
                }
                else if ($scope.graphRates[i].closePosition == "closeShort") {
                    positionVert = parseInt(newVirt) + 15;
                    $scope.drawGraphLine(indx, newVirt, indx, positionVert, "#ff0000");
                }
            }


                prevIndx = indx-5;

                if (indx > 50 && $scope.showBollinger) {

                    newLowerBolVirt = $scope.calculateVert($scope.graphRates[i].lowerBollinger);
                    newUpperBolVirt = $scope.calculateVert($scope.graphRates[i].upperBollinger);

                    $scope.drawGraphLine(prevIndx, previousLowerBollingerVirt, indx, newLowerBolVirt, "#FF9900");
                    $scope.drawGraphLine(prevIndx, previousUpperBollingerVirt, indx, newUpperBolVirt, "#FF9900");
                }


                //SMA Shit

                if (indx > 50 && $scope.showSma) {
                    newShortSmaVirt = $scope.calculateVert($scope.graphRates[i].smaShortIndicator);
                    newLongSmaVirt = $scope.calculateVert($scope.graphRates[i].smaLongIndicator);

                    $scope.drawGraphLine(prevIndx, previousShortSmaVirt, indx, newShortSmaVirt, "#AA00FF");
                    $scope.drawGraphLine(prevIndx, previousLongSmaVirt, indx, newLongSmaVirt, "#800000");
                }

                if (indx > 50 && $scope.showEma) {
                    newShortSmaVirt = $scope.calculateVert($scope.graphRates[i].shortEma);
                    newLongSmaVirt = $scope.calculateVert($scope.graphRates[i].longEma);

                    $scope.drawGraphLine(prevIndx, previousShortSmaVirt, indx, newShortSmaVirt, "#33cc33");
                    $scope.drawGraphLine(prevIndx, previousLongSmaVirt, indx, newLongSmaVirt, "#ff0000");
                }
//
                if (indx > 500) {
                    //RSI Shit
                    newRsi = $scope.calculateRsiVert($scope.graphRates[i].rsi);
                    $scope.drawRsiLine(prevIndx, previousRsi, indx, newRsi, "#006666");

                    $scope.drawRsiLine(0, rsiUpper, 3000, rsiUpper, "#E0E0E0");
                    $scope.drawRsiLine(0, rsiLower, 3000, rsiLower, "#E0E0E0");
                    $scope.drawRsiLine(0, rsiMid, 3000, rsiMid,"#FFFFAA" );

                    newMacd = $scope.calculateMacdVert($scope.graphRates[i].macd);
                    newSignal = $scope.calculateMacdVert($scope.graphRates[i].macdSignal);

                    $scope.drawMacdLine(prevIndx, previousMacd, indx, newMacd, "#63f486");
                    $scope.drawMacdLine(prevIndx, previousSignal, indx, newSignal, "#D3D3D3");
                }


            previousVirt = parseInt(newVirt);
            previousLowerBollingerVirt = parseInt(newLowerBolVirt);
            previousUpperBollingerVirt = parseInt(newUpperBolVirt);
            previousRsi = parseInt(newRsi);

            previousMacd = parseInt(newMacd);
            previousSignal = parseInt(newSignal);

            previousShortSmaVirt = parseInt(newShortSmaVirt);
            previousLongSmaVirt = parseInt(newLongSmaVirt);

            indx = indx + 5;
        }
    }

	$scope.graphHover = function(e) {

		function getMousePos(canvas, evt) {
			var rect = canvas.getBoundingClientRect(), root = document.documentElement;

			// return relative mouse position
			var mouseX = evt.clientX - rect.left - root.scrollLeft;
			var mouseY = evt.clientY - rect.top - root.scrollTop;
			return {
				x: mouseX,
				y: mouseY
			};
		}

		$scope.mousePos = getMousePos(c, e);
		var nearestFive = Math.ceil($scope.mousePos.x/5)*5;

		$scope.mouseDateTime = $scope.graph[nearestFive].tradeDate;
		$scope.mouseTradePrice = $scope.graph[nearestFive].tradeAmount;
        $scope.mouseRsi = $scope.graph[nearestFive].rsi;
        $scope.mouseMacd = $scope.graph[nearestFive].macd;
        $scope.mouseMacdIndicator = $scope.graph[nearestFive].macdIndicator;
        $scope.mouseRoc = $scope.graph[nearestFive].roc;
        $scope.mouseIndex = $scope.graph[nearestFive].index;
	}

    $scope.graphMacdHover = function(e) {

        function getMousePos(canvas, evt) {
            var rect = canvas.getBoundingClientRect(), root = document.documentElement;

            // return relative mouse position
            var mouseX = evt.clientX - rect.left - root.scrollLeft;
            var mouseY = evt.clientY - rect.top - root.scrollTop;
            return {
                x: mouseX,
                y: mouseY
            };
        }

        $scope.mousePos = getMousePos(cMacd, e);
        var nearestFive = Math.ceil($scope.mousePos.x/5)*5;

        $scope.mouseMacdHisto = $scope.graph[nearestFive].macdHistogram;
    }

    $scope.gainLossClass = function(value) {
        if (value > 0) {
            return "gain";
        }
        else {
            return "loss";
        }
    }

    $scope.beginRange = function(newBegin) {
        $scope.ratesBegin = newBegin;


    }

    $scope.transactionShowLeft = function(indx) {
        return parseInt(indx)*5 - 10;
    }

    $scope.endRange = function(newEnd) {
        $scope.ratesEnd = parseInt(newEnd) - 10;
        var rateIndex = $scope.ratesBegin;

        var rateEnd = parseInt($scope.ratesEnd) + 10;

        var maxRate = -1;
        var minRate = 100;

        var maxMacd = -1;
        var minMacd = 100;

        $scope.graphRates = [];

        var graphIndex = 0;

        while (rateIndex < rateEnd) {
            if ($scope.rates[rateIndex].upperBollinger > maxRate) {
                maxRate = $scope.rates[rateIndex].upperBollinger;
            }

            if ($scope.rates[rateIndex].lowerBollinger < minRate) {
                minRate = $scope.rates[rateIndex].lowerBollinger;
            }

            if ($scope.rates[rateIndex].macd > maxMacd || $scope.rates[rateIndex].macdSignal > maxMacd ) {
                if ($scope.rates[rateIndex].macd > $scope.rates[rateIndex].macdSignal) {
                    maxMacd = $scope.rates[rateIndex].macd;
                }
                else {
                    maxMacd = $scope.rates[rateIndex].macdSignal;
                }
            }

            if ($scope.rates[rateIndex].macd < minMacd || $scope.rates[rateIndex].macdSignal < minMacd ) {
                if ($scope.rates[rateIndex].macd < $scope.rates[rateIndex].macdSignal) {
                    minMacd = $scope.rates[rateIndex].macd;
                }
                else {
                    minMacd = $scope.rates[rateIndex].macdSignal;
                }
            }

            if ($scope.rates[rateIndex].lowerBollinger < minRate) {
                minRate = $scope.rates[rateIndex].lowerBollinger;
            }

            $scope.graphRates[graphIndex] = $scope.rates[rateIndex];

            graphIndex++;
            rateIndex++;
        }

        $scope.graphCurMin = parseFloat(minRate) - .0001;
        $scope.graphCurMax = parseFloat(maxRate) + .0001;

        $scope.graphData();
    }

});