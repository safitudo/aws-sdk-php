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

namespace Aws\Common\Exception\Parser;

use Guzzle\Http\Message\Response;

/**
 * Parses default XML exception responses
 */
class DefaultXmlExceptionParser implements ExceptionParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(Response $response)
    {
        $data = array(
            'code'       => null,
            'message'    => null,
            'type'       => $response->isClientError() ? 'client' : 'server',
            'request_id' => null,
            'parsed'     => null
        );

        if ($body = $response->getBody(true)) {
            $this->parseBody(new \SimpleXMLElement($body), $data);
        } else {
            $this->parseHeaders($response, $data);
        }

        return $data;
    }

    /**
     * Parses additional exception information from the response headers
     *
     * @param Response $response The response from the request
     * @param array    $data     The current set of exception data
     */
    protected function parseHeaders(Response $response, array &$data)
    {
        $data['message'] = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
        if ($requestId = $response->getHeader('x-amz-request-id')) {
            $data['request_id'] = $requestId;
            $data['message'] .= " (Request-ID: $requestId)";
        }
    }

    /**
     * Parses additional exception information from the response body
     *
     * @param \SimpleXMLElement $body The response body as XML
     * @param array             $data The current set of exception data
     */
    protected function parseBody(\SimpleXMLElement $body, array &$data)
    {
        $data['parsed'] = $body;

        $tempXml = $body->xpath('//Code[1]');
        $data['code'] = (string) $tempXml[0];

        $tempXml = $body->xpath('//Message[1]');
        $data['message'] = (string) $tempXml[0];

        $tempXml = $body->xpath('//RequestId[1]');
        if (empty($tempXml)) {
            $tempXml = $body->xpath('//RequestID[1]');
        }
        $data['request_id'] = (string) $tempXml[0];
    }
}