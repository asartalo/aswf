<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Template\Helper;

use Asar\Tests\TestCase;
use Asar\Template\Helper\Output;

// Asar\Template\Engine\EngineInterface;

/**
 * Specifications for the Extend template helper
 */
class OutputTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->outputHelper = new Output;
    }

    /**
     * Escapes dirty html output
     */
    public function testEscapesHtml()
    {
        $this->assertEquals(
            '&lt;script&gt;Hello&lt;/script&gt;',
            $this->outputHelper->escape('<script>Hello</script>')
        );
    }



}