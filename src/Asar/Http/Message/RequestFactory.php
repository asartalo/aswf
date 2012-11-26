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
 * A factory for creating Requests
 */
class RequestFactory extends Message
{

    /**
     * Creates requests based on environment variables
     *
     * @param array $server server variable typically $_SERVER
     * @param array $params request parameters typically $_GET
     * @param array $post   request post parameters typically $_POST
     *
     * @return Request
     */
    public function createRequestFromEnvironment($server = array(), $params = array(), $post = null)
    {
        $options = array();
        $options['method'] = $this->getIfExists('REQUEST_METHOD', $server);
        $options['path'] = $this->getIfExists('REQUEST_URI', $server);
        $options['params'] = $params;
        if ($options['method'] === 'POST') {
            $options['content'] = $post;
        }
        $options['headers'] = $this->createHeaders($server);

        return new Request($options);
    }

    private function getIfExists($key, $array)
    {
        return array_key_exists($key, $array) ? $array[$key] : null;
    }

    private function createHeaders($server)
    {
        $headers = array();
        foreach ($server as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[str_replace('HTTP_', '', $key)] = $value;
            }
        }

        return $headers;
    }

}