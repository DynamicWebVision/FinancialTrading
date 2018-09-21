<?php namespace App\Services;

use \Aws;

class AwsService  {

    protected $ec2Client;

    public function __construct() {
        $this->ec2Client = new Aws\Ec2\Ec2Client([
            'region' => 'us-east-1',
            'version'=>'latest'
        ]);
    }

    public function setCurrentServerAttributes() {
        $instance_id = file_get_contents("http://instance-data/latest/meta-data/instance-id");

        $response = $this->ec2Client->describeInstances(['InstanceIds'=>[$instance_id]]);

        if (isset($response->Reservations)) {
            echo 'Reservations Set';
        }

        echo '<BR><BR><BR><BR>';

        if (isset($response[0])) {
            echo '324234 Set';
        }

        echo '<BR><BR><BR><BR>';

        if (isset($response['Reservations'])) {
            echo '444444444 Set';
        }

        echo '<BR><BR><BR><BR>';

    }
}
