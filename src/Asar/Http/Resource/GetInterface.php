<?php

namespace Asar\Http\Resource;

interface GetInterface {
  function GET(\Asar\Http\Message\Request $request);
}