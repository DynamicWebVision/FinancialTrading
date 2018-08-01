<?php namespace App\Services;

class CurrencyPositionDecisions  {

    public $rsiUpperCutoff;
    public $rsiLowerCutoff;
    public $positionDecisions;

    public $lossCutoff;

    public function basicMacdCrossOverPositionDecision($rate, $currentPositionStatus) {
        if ($rate['macdCrossOver'] == 1 && $currentPositionStatus != 1 && $rate['longerValues']['emaStatus'] == "above"

        ) {
            $newPosition = "long";
        }
        else if ($rate['macdCrossOver'] == -1 && $currentPositionStatus != -1 && $rate['longerValues']['emaStatus'] == "below"
           ) {
            $newPosition = "short";
        }
        else {
            $newPosition = "none";
        }

		if ($currentPositionStatus == 1) {
			if ($newPosition == "short" || $rate['positionChangePercent'] > ($this->lossCutoff*3) || $rate['positionChangePercent'] < $this->lossCutoff*-1 ) {
				$closePosition = "closeLong";
			}
			else {
				$closePosition = "hold";
			}
		}
		elseif ($currentPositionStatus == -1) {
			if ($newPosition == "long"  || $rate['positionChangePercent']  < $this->lossCutoff*-1 || $rate['positionChangePercent'] > ($this->lossCutoff*3)) {
				$closePosition = "closeShort";
			}
			else {
				$closePosition = "hold";
			}
		}
		else {
			$closePosition = "none";
		}

        return ['newPosition'=>$newPosition, 'closePosition'=>$closePosition];
    }

    public function smaCrossOverDecision($rate, $currentPositionStatus) {

        if ($rate['smaCrossover'] = 'crossedAbove' && $rate['roc'] > 0
        && $currentPositionStatus != 1
        && $rate['amount'] > $rate['shortSma'] && $rate['rocFactor'] > .8
        ) {
        $newPosition = "long";
        }
        else if ($rate['smaCrossover'] = 'crossedBelow' && $rate['roc'] < 0
            && $currentPositionStatus !=  -1
            && $rate['amount'] < $rate['shortSma']
            && $rate['rocFactor'] > .8) {
            $newPosition = "short";
        }
        else {
            $newPosition = "none";
        }

        if ($currentPositionStatus == 1) {
            if ($rate['rsi'] > $this->rsiUpperCutoff
                || $newPosition == "short" || $rate['bollingerEvent'] == 'crossedAboveToBetween' || $rate['currentLoss'] > $this->lossCutoff

            ) {
                $closePosition = "closeLong";
            }
            else {
                $closePosition = "hold";
            }
        }
        elseif ($currentPositionStatus == -1) {
            if ($rate['rsi'] < $this->rsiLowerCutoff
                || $newPosition == "long" || $rate['bollingerEvent'] == 'crossedBelowToBetween'
                || $rate['currentLoss'] > $this->lossCutoff) {
                $closePosition = "closeShort";
            }
            else {
                $closePosition = "hold";
            }
        }
        else {
            $closePosition = "none";
        }

        return ['newPosition'=>$newPosition, 'closePosition'=>$closePosition];
}

    public function bollingerCrossoverDecision($rate, $currentPositionStatus) {
        if ($rate['bollingerEvent'] == 'crossedAbove' && $currentPositionStatus != 1

        ) {
            $newPosition = "long";
        }
        else if ($rate['bollingerEvent'] == 'crossedBelow' && $currentPositionStatus != -1
              ) {
            $newPosition = "short";
        }
        else {
            $newPosition = "none";
        }

        if ($currentPositionStatus == 1) {
            if ($newPosition == "short"  || $rate['bollingerSmaEvent'] == 'crossed' || $rate['currentLoss'] > $this->lossCutoff) {
                $closePosition = "closeLong";
            }
            else {
                $closePosition = "hold";
            }
        }
        elseif ($currentPositionStatus == -1) {
            if ($newPosition == "long"  || $rate['bollingerSmaEvent'] == 'crossed' || $rate['currentLoss'] > $this->lossCutoff) {
                $closePosition = "closeShort";
            }
            else {
                $closePosition = "hold";
            }
        }
        else {
            $closePosition = "none";
        }

        return ['newPosition'=>$newPosition, 'closePosition'=>$closePosition];
    }


    public function emaDecision($rate, $currentPositionStatus) {
        if ($rate['emaEvent'] == 'crossedAbove' && $currentPositionStatus != 1 && ($rate['rsi'] > 45 && $rate['rsi'] < 55)

        ) {
            $newPosition = "long";
        }
        else if ($rate['emaEvent'] == 'crossedBelow' && $currentPositionStatus != -1 && ($rate['rsi'] > 45 && $rate['rsi'] < 65)
        ) {
            $newPosition = "short";
        }
        else {
            $newPosition = "none";
        }

        if ($currentPositionStatus == 1) {
            if ($newPosition == "short"  || $rate['positionChange']*-1 > $this->lossCutoff || $rate['positionChange'] > $this->lossCutoff*2) {
                $closePosition = "closeLong";
            }
            else {
                $closePosition = "hold";
            }
        }
        elseif ($currentPositionStatus == -1) {
            if ($newPosition == "long"   || $rate['positionChange']*-1 > $this->lossCutoff || $rate['positionChange'] > $this->lossCutoff*2) {
                $closePosition = "closeShort";
            }
            else {
                $closePosition = "hold";
            }
        }
        else {
            $closePosition = "none";
        }
        return ['newPosition'=>$newPosition, 'closePosition'=>$closePosition];
    }

    public function phantomDecision() {
        if ($this->decisionIndicators['emaCrossover'] == 'crossedAbove' &&  ($this->decisionIndicators['rsi'] > 45 && $this->decisionIndicators['rsi'] < 55) && $this->decisionIndicators['rsiLinearRegression'] >= 1) {
            return 'long';
        }
        elseif ($this->decisionIndicators['emaCrossover'] == 'crossedAbove' &&  ($this->decisionIndicators['rsi'] > 45 && $this->decisionIndicators['rsi'] < 55) && $this->decisionIndicators['rsiLinearRegression'] <= -1) {
            return 'short';
        }
        else {
            return 'none';
        }
    }

}