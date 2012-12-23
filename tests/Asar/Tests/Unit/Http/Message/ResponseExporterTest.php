<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Http\Message;

use Asar\TestHelper\TestCase;
use Asar\Http\Message\Response;
use Asar\Http\Message\ResponseExporter;

/**
 * A specification for ResponseExporter
 */
class ResponseExporterTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->exporter = new ResponseExporter;
    }

    /**
     * Exports content of response
     */
    public function testExportOutputsContentOfResponse()
    {
        $response = new Response;
        $response->setContent('The quick brown fox.');
        ob_start();
        $this->exporter->exportResponse($response);
        $content = ob_get_clean();
        $this->assertEquals(
            'The quick brown fox.', $content
        );
    }

    /**
     * Export calls header function wrapper
     */
    public function testExportResponseHeadersUsesHeaderFunctionWrapper()
    {
        $exporter = $this->quickMock(
            'Asar\Http\Message\ResponseExporter', array('header')
        );
        $exporter->expects($this->exactly(3))
            ->method('header');
        $exporter->expects($this->at(0))
            ->method('header')
            ->with('Content-Type: text/plain');
        $exporter->expects($this->at(1))
            ->method('header')
            ->with('Content-Encoding: gzip');
        $exporter->expects($this->at(2))
            ->method('header')
            ->with('HTTP/1.1 404 Not Found');

        $response = new Response(
            array(
                'headers' => array(
                    'Content-Type' => 'text/plain',
                    'Content-Encoding' => 'gzip'
                ),
                'status' => 404

            )
        );

        $exporter->exportResponse($response);
    }

}