<?php namespace App\Http\Controllers\Marketing;

use Request;
use View;
use DB;
use App\Model\Rentals\PossibleRental;
use App\Model\Rentals\VwPossibleRentalsDowntown;
use App\Model\Rentals\PossibleRentalAgent;
use App\Http\Controllers\ProcessScheduleController;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use Redirect;
use Aws;
use Session;
use \Log;
use App\Services\MailboxLayer;
use App\Services\Utility;
use App\Services\ProcessLogger;

class EmailController extends Controller {

    public function index() {
        return View::make('rentals');
    }

    public function validateOneEmail() {
        $mailboxLayer = new MailboxLayer();

        $mailboxLayer->validateEmail('CATERING@SUSHIEATSTATION.COM');
    }
}