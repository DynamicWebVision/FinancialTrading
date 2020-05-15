<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CurrencyIndicators;
use App\Model\HistoricalRates;
use App\Model\TmpTestRates;
use App\Model\Servers\ServerEnvironmentDef;
use App\Model\ProcessScheduleDefinition;
use App\Model\ProductAdvice\ProductTypeQuestion;
use App\Model\ProductAdvice\ProductTypeQuestionAnswer;
use App\Http\Controllers\ProcessScheduleController;
use Illuminate\Support\Facades\Config;
use App\Model\Servers;
use App\Broker\OandaV20;
use App\Services\StringHelpers;

class ProcessScheduleControllerTest extends TestCase
{

    public $transactionController;
    public $oanda;

    public function testCheckForDueProcesses() {
        $serversController = new ProcessScheduleController();
        $serversController->checkForDueProcesses();
    }

    public function testCreateMultiRecords() {
        $serversController = new ProcessScheduleController();
        $serversController->createQueueRecordsWithVariableIds('yahoo_price',
            [1]
            );
    }

    public function testCreateProcessRecords() {
        $serversController = new ProcessScheduleController();

        $processSchedule = ProcessScheduleDefinition::find(6)->toArray();
        $serversController->createProcessRecords($processSchedule);
    }

    public function markChildrenUnprocessed($questions, $index){

        while (isset($questions[$index])) {
            $questions[$index]['answers'][0]['processed'] = false;
            $questions[$index]['answers'][1]['processed'] = false;

            $questions[$index]['answers'][0]['parentQuestion'] = false;
            $questions[$index]['answers'][1]['parentQuestion'] = false;

            $index = $index + 1;
        }
        return $questions;
    }


    public function array_cartesian_product($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++)
        {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn -1); $j >= 0; $j --)
            {
                if (next($arrays[$j]))
                    break;
                elseif (isset ($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

    public function testDumpSequences() {
        $finalAnswers = ProductTypeQuestionAnswer::where('result_product_type_question_id', '=', null)->get();

        $sequences = [];

        $fullString = '<table>';

        foreach ($finalAnswers as $finalAnswer) {

            $string = '<tr><td>'.$finalAnswer->id.'</td><td></td>';

            $currentAnswer = $finalAnswer;

            while ($currentAnswer) {
                $string .= "<td>".$currentAnswer->text."</td>";

                $currentAnswer = ProductTypeQuestionAnswer::where('result_product_type_question_id', '=', $currentAnswer->product_type_question_id)->first();
            }
            $fullString .= $string." </tr>";
        }
        $fullString .= '</table>';
        dd($fullString);

    }

    public function testLoadProducts() {
        $firstAnswers = [['Less than 2,000 square feet', '2,000+ square feet'],
        ['1', '2'],
        ['Basic internet for work related tasks and browsing', 'Netflix, Hulu, Youtube, AppleTV'],
        ['Less than 5', 'More than 5'],
        ['I am looking for the lowest cost option', 'I am looking for good value and good quality']];

        $validated = $this->array_cartesian_product($firstAnswers);



        $questions = [['name'=>'What is the size of space WiFi will be covering?' , 'answers'=> [['processed_1'=> false, 'processed_2'=> false, 'parentQuestion'=> 1,'name'=> 'Less than 2,000 square feet'], ['processed_1'=> false, 'processed_2'=> false, 'parentQuestion'=> 1, 'name'=> '2,000+ square feet']]],
        ['name'=>'How many stories of the space are you covering?' , 'answers'=> [['processed_1'=> false, 'processed_2'=> false, 'name'=> '1'], ['processed_1'=> false, 'processed_2'=> false, 'name'=> '2']]],
        ['name'=>'What type of content do you stream?' , 'answers'=> [['processed_1'=> false, 'processed_2'=> false, 'name'=> 'Basic internet for work related tasks and browsing'], ['processed_1'=> false, 'processed_2'=> false, 'name'=> 'Netflix, Hulu, Youtube, AppleTV']]],
        ['name'=>'How many devices will be connected at one time?' , 'answers'=> [['processed_1'=> false, 'processed_2'=> false, 'name'=> 'Less than 5'], ['processed_1'=> false, 'processed_2'=> false, 'name'=> 'More than 5']]],
        ['name'=>'What is your budget?' , 'answers'=> [['processed_1'=> false, 'processed_2'=> false, 'name'=> 'I am looking for the lowest cost option'], ['processed_1'=> false, 'processed_2'=> false, 'name'=> 'I am looking for good value and good quality']]]];


        $allProcessed = false;

        $questionIndex = 0;
        $answerIndex = 0;

        $direction = 'down';

        $parentQuestionId = 1;

        $nextParentQuestions = [1];

        foreach ($questions as $index=>$question) {
            $parentQuestions = $nextParentQuestions;
            $nextParentQuestions = [];

            foreach ($question['answers'] as $answer) {

                foreach ($parentQuestions as $parentQuestion) {

                    if (isset($questions[$index+1])) {
                        $resultQuestion =  new ProductTypeQuestion();

                        $resultQuestion->product_type_id = 57;

                        $resultQuestion->text = $questions[$index+1]['name'];

                        $resultQuestion->save();

                        $newAnswer = new ProductTypeQuestionAnswer();

                        $newAnswer->product_type_question_id = $parentQuestion;

                        $newAnswer->result_product_type_question_id = $resultQuestion->id;

                        $newAnswer->text = $answer['name'];

                        $newAnswer->save();

                        $nextParentQuestions[] = $resultQuestion->id;
                    }
                    else {
                        $newAnswer = new ProductTypeQuestionAnswer();

                        $newAnswer->product_type_question_id = $parentQuestion;

                        $newAnswer->text = $answer['name'];

                        $newAnswer->save();
                    }


                }


            }
        }


//        while (!$allProcessed) {
//
//            if ($direction == 'down') {
//                $nextQuestionIndex = $questionIndex + 1;
//
//                if (isset($questions[$nextQuestionIndex])) {
//                    $nextQuestion = new ProductTypeQuestion();
//
//                    $nextQuestion->product_type_id = 57;
//
//                    $nextQuestion->text = $questions[$nextQuestionIndex]['name'];
//
//                    $nextQuestion->save();
//
//                    $answer = new ProductTypeQuestionAnswer();
//
//                    $answer->product_type_question_id = $parentQuestionId;
//
//                    $answer->result_product_type_question_id = $nextQuestion->id;
//
//                    $answer->text = $questions[$questionIndex]['answers'][$answerIndex]['name'];
//
//                    $answer->save();
//
//                    $questions[$questionIndex]['answers'][$answerIndex]['parentQuestion'] = $parentQuestionId;
//                    $questions[$questionIndex]['answers'][$answerIndex]['processed'] = true;
//
//                    $questionIndex = $nextQuestionIndex;
//
//                    $parentQuestionId = $nextQuestion->id;
//
//                }
//                else {
//                    $answer = new ProductTypeQuestionAnswer();
//
//                    $answer->product_type_question_id = $parentQuestionId;
//
//                    $answer->text = $questions[$questionIndex]['answers'][0]['name'];
//
//                    $answer->save();
//
//                    $answer = new ProductTypeQuestionAnswer();
//
//                    $answer->product_type_question_id = $parentQuestionId;
//
//                    $answer->text = $questions[$questionIndex]['answers'][1]['name'];
//
//                    $answer->save();
//
//                    $direction = 'up';
//
//                    $questionIndex = $questionIndex - 1;
//                }
//            }
//            else {
//                if (!isset($questions[$questionIndex])) {
//                    $allProcessed = true;
//                }
//                else {
//                    if ($questions[$questionIndex]['answers'][1]['processed']) {
//                        $questionIndex = $questionIndex - 1;
//                    }
//                    else {
//                        $answerIndex = 1;
//                        $direction = 'down';
//                        $parentQuestionId = $questions[$questionIndex]['answers'][0]['parentQuestion'];
//
//                        $questions = $this->markChildrenUnprocessed($questions, $questionIndex);
//                    }
//                }
//            }
//
//        }
    }
}
