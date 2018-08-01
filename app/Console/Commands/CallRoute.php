<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Http\Request;

class CallRoute extends Command {

    protected $name = 'route:call';
    protected $description = 'Call route from CLI';

    public function __construct()
    {
        parent::__construct();
    }

    public function fire()
    {
        $request = Request::create($this->option('uri'), 'GET');
        $this->info(app()['Illuminate\Contracts\Http\Kernel']->handle($request));
    }

    protected function getOptions()
    {
        return [
            ['uri', null, InputOption::VALUE_REQUIRED, 'The path of the route to be called', null],
        ];
    }

}