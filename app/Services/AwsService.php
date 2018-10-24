<?php namespace App\Services;

use \Aws;

class AwsService  {

    protected $ec2Client;
    public $currentInstance;

    public function __construct() {
        $this->ec2Client = new Aws\Ec2\Ec2Client([
            'region' => 'us-east-1',
            'version'=>'latest'
        ]);
    }

    public function setCurrentServerAttributes() {
        $instance_id = file_get_contents("http://instance-data/latest/meta-data/instance-id");

        $response = $this->ec2Client->describeInstances(['InstanceIds'=>[$instance_id]]);

        $this->currentInstance = $response['Reservations'][0]['Instances'][0];
    }

    public function getAllInstances() {
        $response = $this->ec2Client->describeInstances();

        $db_ip_address = $this->getReservationIPWithTag($response['Reservations'], 'finance_db');
    }

    public function getInstanceTagValue($tagKey) {
        foreach ($this->currentInstance['Tags'] as $tag) {
            if ($tag['Key'] == $tagKey) {
                return $tag['Value'];
                break;
            }
        }
    }

    public function getReservationIPWithTag($reservations, $tag) {

        foreach ($reservations as $reservation) {
            dd($reservation['Instances'][0]);
        }
    }
}
