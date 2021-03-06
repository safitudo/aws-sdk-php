<?php
/**
 * Copyright 2010-2012 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Aws\Tests\CloudFront\Waiter;

use Guzzle\Http\Message\Response;

/**
 * @covers Aws\CloudFront\Waiter\DistributionDeployed
 */
class DistributionDeployedTest extends \Guzzle\Tests\GuzzleTestCase
{
    public function testReturnsTrueIfDeployed()
    {
        $client = $this->getServiceBuilder()->get('cloudfront');
        $this->setMockResponse($client, array(
            'cloudfront/GetDistribution_InProgress',
            'cloudfront/GetDistribution_Deployed'
        ));
        $client->waitUntil('distribution_deployed', 'foo', array(
            'interval' => 0
        ));
    }

    /**
     * @expectedException \Aws\Common\Exception\RuntimeException
     * @expectedExceptionMessage Maximum number of failures while waiting: 1
     */
    public function testDoesNotBufferOtherExceptions()
    {
        $client = $this->getServiceBuilder()->get('cloudfront');
        $this->setMockResponse($client, array(new Response(404)));
        $client->waitUntil('distribution_deployed', 'foo');
    }
}
