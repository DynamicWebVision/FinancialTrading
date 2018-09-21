<?php namespace App\Services;

use \Aws;

class AwsService  {

    protected $ec2Client;

    public function __construct($instance_id) {
        $this->ec2Client = new Aws\Ec2\Ec2Client([
            'region' => 'us-east-1',
            'version'=>'latest'
        ]);
    }

    public function setCurrentServerAttributes() {
        $instance_id = file_get_contents("http://instance-data/latest/meta-data/instance-id");

        $response = $this->ec2Client->describeInstances(['InstanceIds'=>[$instance_id]]);

        echo json_encode($response);

    }
}
