<?php

namespace Repository;

class ErrorLogs extends Repository
{
    public function addErrorLog(string $message, string $file, int $line, int $datetime): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO error_logs (message, file, line, datetime) VALUES (:message, :file, :line, :datetime)");
        $stmt->execute([':message' => $message, ':file' => $file, ':line' => $line, ':datetime' => $datetime]);
    }
}