<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Http\Resource;

use Asar\Http\Resource\GetInterface;
use Asar\Http\Resource\PostInterface;
use Asar\Http\Resource\PuttInterface;
use Asar\Http\Resource\DeleteInterface;

/**
 * An abstract class for basic resource interface
 */
abstract class StandardResource implements GetInterface, PostInterface, PutInterface, DeleteInterface
{

}