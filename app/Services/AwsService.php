<?php namespace App\Services;

use \Aws;

class AwsService  {

    public function __construct() {
        $ec2 = new Aws\Ec2\Ec2Client([
            'region' => 'us-east-1'
        ]);
        echo 'asdfasdf';
    }
}
