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

namespace Aws\DynamoDb\Model\BatchRequest;

use Aws\Common\ToArrayInterface;

/**
 * An interface used to describe a batch write request
 */
interface WriteRequestInterface extends ToArrayInterface
{
    /**
     * Returns the table name that the write request is for.
     *
     * @return string The table name
     */
    public function getTableName();
}