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
 * An HTTP Response
 */
class Response extends Message
{
    private $status = 200;

    /**
     * Status phrases
     *
     * These status reason phrases are based on Apache's
     */
    public static $reasonPhrases = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable'
    );

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setIfExists('status', $options, 'setStatus');
    }

    /**
     * Set the response status code
     *
     * @param integer $status the status code
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get the response status
     *
     * @return integer the status code
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the response status reason phrase for this response
     *
     * @return string the status phrase message
     */
    public function getStatusReasonPhrase()
    {
        return self::getReasonPhrase($this->getStatus());
    }

    /**
     * Get the status phrase for a status code
     *
     * @param integer $status the status code
     *
     * @return mixed the reason phrase or null for unknown status codes
     */
    public static function getReasonPhrase($status)
    {
        if (isset(self::$reasonPhrases[$status])) {
            return self::$reasonPhrases[$status];
        }
    }

    /**
     * Imports a raw HTTP response string
     *
     * @param string $rawResponseString
     */
    public function import($rawResponseString)
    {
        $rawarr = explode("\r\n\r\n", $rawResponseString, 2);
        $this->setContent(array_pop($rawarr));
        $headers = explode("\r\n", $rawarr[0]);
        $responseLine = array_shift($headers);
        $this->setStatus(intval(str_replace('HTTP/1.1 ', '', $responseLine)));
        foreach ($headers as $line) {
            $header = explode(':', $line, 2);
            $this->setHeader($header[0], ltrim($header[1]));
        }
    }

}
