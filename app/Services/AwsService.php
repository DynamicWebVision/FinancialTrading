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

        return $response['Reservations'];


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
            $firstInstance = $reservation['Instances'][0];

            foreach ($firstInstance['Tags'] as $instanceTag) {
                if ($instanceTag['Key'] == $tag) {
                    return $firstInstance['PublicIpAddress'];
                    break;
                }
            }
        }
    }

    public function modifyInstanceName($name) {
        $instanceId = file_get_contents("http://instance-data/latest/meta-data/instance-id");

        $test = $this->ec2Client->describeInstances(['InstanceIds'=>[$instanceId]]);

        dd($test);

//        $modify_instance = $this->ec2Client->modifyInstanceAttribute(
//            [
//                'Attribute'=> 'rootDeviceName',
//                'InstanceId'=> $instanceId,
//                'Value'=>$name
//            ]
//        );
    }

    public function getCurrentInstance() {
        $instance_id = file_get_contents("http://instance-data/latest/meta-data/instance-id");

        $response = $this->ec2Client->describeInstances(['InstanceIds'=>[$instance_id]]);

        return $response['Reservations'][0]['Instances'][0];
    }
}
