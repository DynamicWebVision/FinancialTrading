<?php namespace App\Services;

use \Aws;
use \Log;

class AwsService  {

    protected $ec2Client;
    public $currentInstance;
    public $logger;

    public function __construct() {
        $this->ec2Client = new Aws\Ec2\Ec2Client([
            'region' => 'us-east-1',
            'version'=>'latest'
        ]);
    }

    public function setCurrentServerAttributes() {
        try {
            $instance_id = file_get_contents("http://169.254.169.254/latest/meta-data/instance-id");
        }
        catch (\Exception $e) {
            Log::emergency($e->getMessage());
        }

        $response = $this->ec2Client->describeInstances(['InstanceIds'=>[$instance_id]]);

        $this->currentInstance = $response['Reservations'][0]['Instances'][0];

        Log::emergency('Current Instance '.$this->currentInstance);

    }

    public function getAllInstances() {
        $response = $this->ec2Client->describeInstances();

        return $response['Reservations'];

    }

    public function getInstanceTagValue($tagKey) {
        Log::emergency($this->currentInstance['Tags']);
        foreach ($this->currentInstance['Tags'] as $tag) {
            if ($tag['Key'] == $tagKey) {
                return $tag['Value'];
                break;
            }
        }
        return false;
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

    public function getReservationIdWithTag($reservations, $tag) {

        foreach ($reservations as $reservation) {
            $firstInstance = $reservation['Instances'][0];

            foreach ($firstInstance['Tags'] as $instanceTag) {
                if ($instanceTag['Key'] == $tag) {
                    return $firstInstance['InstanceId'];
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
                    'AllocationStrategy' => 'capacityOptimized',
                    //'ClientToken' => '<string>',
                    'IamFleetRole' => 'arn:aws:iam::605160916686:role/aws-service-role/spotfleet.amazonaws.com/AWSServiceRoleForEC2SpotFleet', // REQUIRED
                    'InstanceInterruptionBehavior' => $requestParams['interruption_behavior'],
                    'LaunchSpecifications' => [
                        [
//                            'IamInstanceProfile' => [
//                                'Name' => 'Ec2Manager',
//                            ],
//                            'ImageId' => $requestParams['image_id'],
//                            'InstanceType' => $requestParams['instance_type']
                        ],
                        'Placement' => [
                            'AvailabilityZone' => 'us-east-1',
                        ],

//                        'TagSpecifications' => [
//                            'Tags' => $requestParams['tags'],
//                        ],
//                        'UserData' =>
//                            ['Value'=>'IyEvYmluL2Jhc2gKY2QgL3Zhci93d3cvRmluYW5jaWFsVHJhZGluZyAgJiYgZ2l0IGZldGNoICYmIHN1ZG8gLXUgZWMyLXVzZXIgZ2l0IHB1bGwgb3JpZ2luIG1hc3RlciAmJiBzdWRvIHNlcnZpY2UgaHR0cGQgc3RhcnQgJiYgc3VkbyAtdSBlYzItdXNlciBwaHAgYXJ0aXNhbiB1cGRhdGVfZ2l0X3B1bGxfdGltZSAmJiBzdWRvIC11IGVjMi11c2VyIHBocCBhcnRpc2FuIHVwZGF0ZV9kYl9ob3N0ICYmIHN1ZG8gLXUgZWMyLXVzZXIgcGhwIGFydGlzYW4gdXBkYXRlX3NlcnZlcl9lbnZpcm9ubWVudCAmJiBjb21wb3NlciBpbnN0YWxsICYmIHBocCBhcnRpc2FuIGNsZWFyLWNvbXBpbGVkICYmIHBocCBhcnRpc2FuIG9wdGltaXpl',
//                    ]
                    ],
                    'LaunchTemplateConfigs' => [
                            [
                                'LaunchTemplateSpecification' => [
                                    'LaunchTemplateId' => $requestParams['template_id'],
                                    'Version' => $requestParams['template_version'],
                                ]
                // ...
                        ],
                 ],
            // ...
                    'OnDemandAllocationStrategy' => 'lowestPrice',
                    'TargetCapacity' => $requestParams['server_count'], // REQUIRED
                    'TerminateInstancesWithExpiration' => true,
                    'ValidUntil' => $requestParams['valid_until'],
                ]
            ]);

            return $awsResponse;
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }

    }

    public function createImage($instance_id) {
        $result = $this->ec2Client->createImage([
                'InstanceId' => $instance_id,
                'Name' => 'FIN_DB_BACKUP_'.date('Y-m-d').'_'.time()
            ]);

        return $result;
    }

    public function getImages() {
        $result = $this->ec2Client->describeImages();

        dd($result);
    }
}
