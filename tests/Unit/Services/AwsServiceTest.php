<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

use \App\Services\AwsService;

class AwsServiceTest extends TestCase
{
//    public function testGetInstance()
//    {
//        $awsService = new AwsService();
//
//        $instance = $awsService->getCurrentInstance();
//        dd($instance);
//    }

    public function testGetIpAddressWithTag()
    {
        $awsService = new AwsService();

        $instance = $awsService->getCurrentInstanceIp();

        $this->assertTrue(strlen($instance) > 3);
    }

    public function testCreateSpotRequest()
    {
        $awsService = new AwsService();

        $params = [
            'server_count' => 1,
            'interruption_behavior'=>'terminate',
            'image_id' => 'ami-0bf51fd46fb140e1d',
            'instance_type'=> 't3.nano',
            'tags'=> [
                'Key' => 'test_key',
                'Value' => 'yay',
            ]
        ];

        $awsService->requestSpotFleet($params);
    }
}
