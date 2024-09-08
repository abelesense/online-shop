<?php

namespace Service;

use Throwable;

interface ErrorInterface
{
    public function error(\Throwable $e);

}