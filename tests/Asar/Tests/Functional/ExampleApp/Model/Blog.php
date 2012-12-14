<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Functional\ExampleApp\Model;

/**
 * Sample blog model
 */
class Blog
{

    /**
     * Returns the blog page contents
     * 
     * @return array the blog contents
     */
    public function getContents()
    {
        return array(
            'title' => "The Blog"
        );
    }

}