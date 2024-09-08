<?php

namespace Service;

use Repository\ErrorLogs;
use Throwable;

class TableLoggerService implements ErrorInterface
{
    public function error(\Throwable $e): void
    {

        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $datetime = date('Y-m-d H:i:s');

        $errorLog = new ErrorLogs();
        $errorLog->addErrorLog($message, $file, $line, $datetime);
    }

}