<?php namespace App\Services;

use \Aws;

class AwsService  {

    public function __construct($instance_id) {
        $ec2 = new Aws\Ec2\Ec2Client([
            'region' => 'us-east-1',
            'version'=>'latest'
        ]);
        dd($ec2->describeInstances(['InstanceIds'=>[$instance_id]]));
    }
}
