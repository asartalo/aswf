<?php

namespace Asar\Http\Resource;

interface PostInterface {
  function POST(\Asar\Http\Message\Request $request);
}