<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Config;
use Twilio;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\DB;
use App\Services\TextMessage;

use \Log;
use App\Model\BackTestToBeProcessed;
use App\Model\ProdException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        $reportException = parent::report($exception);

        if (env('APP_ENV') == 'utility') {
            $back_test_id = Config::get('back_test_process_id');
            $back_test_job = Config::get('back_test_job');

            if (!is_null($back_test_id)) {
                Log::emergency('Exception for back_test_id '.$back_test_id.' during job '.$back_test_job);

                $backTestToBeProcessed = BackTestToBeProcessed::find($back_test_id);

                if ($back_test_job == 'full_test_run') {
                    $back_test_unix_time = Config::get('bt_rate_time');

                    $backTestToBeProcessed->run_exception = 1;
                    $backTestToBeProcessed->exception_unix_time = $back_test_unix_time;
                    $backTestToBeProcessed->save();
                }
                elseif ($back_test_job == 'stats') {
                    $backTestToBeProcessed->stats_exception = 1;
                    $backTestToBeProcessed->save();
                }
            }
        }

        if (env('APP_ENV') == 'live_trading') {
            $prodException = new ProdException();
            $prodException->exception = $exception;
            $prodException->save();


            $textMessage = new TextMessage();

            date('m/d H:m');

            try {
                $textMessage->sendTextMessage('Error on '.env('APP_ENV').substr($exception,0,100));
            }
            catch (\Exception $e) {
                \Log::emergency('Twilio Exception'.$e);
            }

        }

        return $reportException;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
