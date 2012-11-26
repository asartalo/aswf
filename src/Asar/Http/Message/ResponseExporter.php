<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Http\Message;

/**
 * Exports a Response object as a proper HTTP Response
 */
class ResponseExporter
{
    /**
     * Exports a Response object
     *
     * @param Response $response a Response object
     */
    public function exportResponse(Response $response)
    {
        $headers = $response->getHeaders();
        foreach ($headers as $key => $value) {
            $this->header("$key: $value");
        }
        // TODO: fix scheme export
        $this->header(
            "HTTP/1.1 {$response->getStatus()} {$response->getStatusReasonPhrase()}"
        );
        echo $response->getContent();
    }

    /**
     * A wrapper method for the header() native PHP function
     *
     * This is used for testability reasons.
     *
     * @param string $headerValue the header value to set
     */
    public function header($headerValue)
    {
        @header($headerValue);
    }
}