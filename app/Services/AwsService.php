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
    }

    public function getCurrentInstanceIp() {
        if (env('APP_ENV') == 'local') {
            return 'LOCAL';
        }

        try {
            $instance_id = file_get_contents("http://instance-data/latest/meta-data/instance-id");

            $response = $this->ec2Client->describeInstances(['InstanceIds'=>[$instance_id]]);

            return $response['Reservations'][0]['Instances'][0]['PublicIpAddress'];
        }
        catch (\Exception $e) {
            return 'IP_ERROR';
        }
    }

    public function requestSpotFleet($requestParams) {

        try {
            $awsResponse = $this->ec2Client->requestSpotFleet([
                'SpotFleetRequestConfig' => [ // REQUIRED
                    'AllocationStrategy' => 'lowestPrice',
                    //'ClientToken' => '<string>',
                    'FulfilledCapacity' => $requestParams['server_count'],
                    'IamFleetRole' => 'aws-ec2-spot-fleet-tagging-role', // REQUIRED
                    'InstanceInterruptionBehavior' => $requestParams['interruption_behavior'],
                    'LaunchSpecifications' => [
                        [
                            'IamInstanceProfile' => [
                                'Arn' => 'arn:aws:iam::605160916686:role/Ec2Manager',
                                'Name' => 'Ec2Manager',
                            ],
                            'ImageId' => $requestParams['image_id'],
                            'InstanceType' => $requestParams['instance_type']
                        ],
                        'Placement' => [
                            'AvailabilityZone' => 'us-east-1',
                        ],
                        'SecurityGroups' => [
                            'GroupId' => 'sg-4d724628',
                            'GroupName' => 'default',
                        ],

                        'TagSpecifications' => [
                            'Tags' => $requestParams['tags'],
                        ],
                    ],
                    'OnDemandAllocationStrategy' => 'lowestPrice',
                    'TargetCapacity' => $requestParams['server_count'], // REQUIRED
                    'TerminateInstancesWithExpiration' => true
                ]
            ]);

            dd($awsResponse);
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }

    }
}
