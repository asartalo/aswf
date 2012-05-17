<?php
namespace Asar\Http;

interface RequestHandlerInterface {

  /**
   * @param Asar\Http\Request $request A request object
   * @return Asar\Http\Response A response object
   */
  function handleRequest(Message\Request $request);
}
