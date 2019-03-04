<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Services\AwsService;

class AwsServiceTest extends TestCase
{
    public function testGetInstance()
    {
        $awsService = new AwsService();

        $instance = $awsService->getCurrentInstance();
        dd($instance);
    }
}
