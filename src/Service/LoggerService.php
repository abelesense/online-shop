<?php

namespace Service;

class LoggerService
{
    public function error($e)
    {
        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $datetime = date('Y-m-d H:i:s');

// Создаем строку для записи в файл
        $logMessage = "Message: $message\n" .
            "In file: $file\n" .
            "On line: $line\n" .
            "Exception occurred at: $datetime\n" .
            "-----------------------------\n";

// Записываем информацию в файл text.txt
        file_put_contents('../Storage/logs/errors.txt', $logMessage, FILE_APPEND);
    }
}